using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using System.Collections.Generic;
using System;
using System.Threading.Tasks;
namespace Ark.AppService
{
   public class AffiliateAppService
    {
        public AffiliateMapBO GetAffiliateLink(AffiliateMapBO affiliateMapBO)
        {
            UserAppService userAppService = new UserAppService();

            var _bsi = userAppService.Get(new TblUserAuth { UserName = affiliateMapBO.BinarySponsorID });
            var _dsi = userAppService.Get(new TblUserAuth { UserName = affiliateMapBO.DirectSponsorID });
            affiliateMapBO.BinarySponsorID = _bsi != null ? _bsi.Uid : throw new ArgumentException("Binary sponsor is invalid");
            affiliateMapBO.DirectSponsorID = _dsi != null ? _dsi.Uid : throw new ArgumentException("Introducer is invalid");

            return affiliateMapBO;
        }
        public bool ComputeCommissions(TblUserAuth userAuth, decimal _amountPaid, ArkContext db = null)
        {
            using (db = new ArkContext())
            {
                using var transaction = db.Database.BeginTransaction();
                UserBusinessPackageRepository userBusinessPackageRepository = new UserBusinessPackageRepository();

                List<TblUserBusinessPackage> userBusinessPackages = userBusinessPackageRepository.GetAllUserPackages(userAuth, db);

                TblUserBusinessPackage tblUserBusinessPackage = userBusinessPackageRepository.Get(userBusinessPackages[0], db);

                UserIncomeAppService userIncomeAppService = new UserIncomeAppService();
                userIncomeAppService.ExecuteCommissionDistribution(new TblUserAuth { Id = (long)tblUserBusinessPackage.UserAuthId }, tblUserBusinessPackage, _amountPaid, db);

                transaction.Commit();
                return true;
            }
        }
        
    }
}
