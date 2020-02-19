using System;
using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using System.Collections.Generic;

namespace Ark.DataAccessLayer
{
    public class UserMapRepository
    {
        public bool Create(TblUserMap userMapQuery, ArkContext db)
        {
            db.TblUserMap.Add(userMapQuery);
            db.SaveChanges();

            return true;

        }
        public TblUserMap Get(TblUserAuth userAuth, ArkContext db)
        {
            var _q = from a in db.TblUserMap
                     where a.Id == userAuth.Id
                     join b in db.TblUserAuth on a.Id equals b.Id
                     join c in db.TblUserInfo on b.UserInfoId equals c.Id
                     select new TblUserMap
                     {
                         Id = a.Id,
                         CreatedOn = a.CreatedOn,
                         IsEnabled = a.IsEnabled,
                         ModifiedOn = a.ModifiedOn,
                         UserUid = a.UserUid,
                         Position = a.Position,
                         SponsorUserId = a.SponsorUserId,
                         UplineUserId = a.UplineUserId,
                         IdNavigation = new TblUserAuth { Id = b.Id, UserName = b.UserName, UserInfo = c}
                     };

            TblUserMap _qRes = _q.FirstOrDefault();

            return _qRes;

        }
        public List<TblUserMap> GetAll(TblUserMap userMapQuery, ArkContext db)
        {
            var _q = from a in db.TblUserMap
                     where a.Id == userMapQuery.Id || a.UserUid == userMapQuery.UserUid || a.UplineUserId == userMapQuery.UplineUserId || a.SponsorUserId == userMapQuery.SponsorUserId
                     join b in db.TblUserAuth on a.Id equals b.Id
                     select new TblUserMap
                     {
                         Id = a.Id,
                         CreatedOn = a.CreatedOn,
                         IsEnabled = a.IsEnabled,
                         ModifiedOn = a.ModifiedOn,
                         UserUid = a.UserUid,
                         Position = a.Position,
                         SponsorUserId = a.SponsorUserId,
                         UplineUserId = a.UplineUserId,
                         IdNavigation = a.IdNavigation
                     };

            List<TblUserMap> _qRes = _q.ToList<TblUserMap>();

            return _qRes;
        }
        public List<TblUserBusinessPackage> GetAllActivated(TblUserMap userMapQuery, ArkContext db)
        {
            var _q = from a in db.TblUserMap
                     join b in db.TblUserAuth on a.Id equals b.Id
                     join c in db.TblUserBusinessPackage on b.Id equals c.UserAuthId
                     join d in db.TblBusinessPackage on c.BusinessPackageId equals d.Id

                     where a.SponsorUserId == userMapQuery.SponsorUserId && c.PackageStatus == PackageStatus.Activated
                     select new TblUserBusinessPackage
                     {
                         Id = a.Id,
                         CreatedOn = c.CreatedOn,
                         IsEnabled = c.IsEnabled,
                         ModifiedOn = c.ModifiedOn,
                         ActivationDate = c.ActivationDate,
                         BusinessPackageId = c.BusinessPackageId,
                         UserAuthId = c.UserAuthId,
                         CancellationDate = c.CancellationDate,
                         ExpiryDate = c.ExpiryDate,
                         UserDepositRequestId = c.UserDepositRequestId,
                         PackageStatus = c.PackageStatus,
                         BusinessPackage = d
                     };

            List<TblUserBusinessPackage> _qRes = _q.ToList<TblUserBusinessPackage>();

            return _qRes;
        }
        private List<UserMapBO> GetMapChildren(TblUserAuth userAuth)
        {
            UserMapRepository userMapRepository = new UserMapRepository();
            UserInfoRepository userInfoRepository = new UserInfoRepository();
            UserBusinessPackageRepository userBusinessPackageRepository = new UserBusinessPackageRepository();

            using ArkContext db = new ArkContext();
            TblUserInfo userInfo = userInfoRepository.Get(userAuth, db);

            var _q = from a in db.TblUserMap
                     join b in db.TblUserAuth on a.Id equals b.Id
                     join c in db.TblUserInfo on b.UserInfoId equals c.Id

                     where a.UplineUserId == userAuth.Id
                     orderby a.Position ascending
                     select new TblUserMap
                     {
                         Id = a.Id,
                         CreatedOn = a.CreatedOn,
                         IsEnabled = a.IsEnabled,
                         Position = a.Position,
                         UplineUserId = a.UplineUserId,
                         IdNavigation = new TblUserAuth { Id = b.Id, CreatedOn = b.CreatedOn, IsEnabled = b.IsEnabled, UserName = b.UserName, UserAlias = b.UserAlias, UserInfo = c },
                     };

            List<TblUserMap> _qRes = _q.ToList();
            List<UserMapBO> userMapChildren = new List<UserMapBO>();

            for (int i = 0; i < _qRes.Count; i++)
            {
                UserMapBO userMap = new UserMapBO();
                List<TblUserBusinessPackage> userBusinessPackages = userBusinessPackageRepository.GetAllUserPackages(_qRes[i].IdNavigation, db);
                userBusinessPackages = userBusinessPackages.FindAll(i => i.UserDepositRequest.DepositStatus == (short)DepositStatus.Paid);
                userMap.title = userBusinessPackages.Count > 0 ? @String.Format("BP ({0:#,##0.000})", userBusinessPackages.Sum(i => i.UserDepositRequest.Amount)) : "Inactive";
                userMap.name = _qRes[i].IdNavigation.UserName;
                userMap.relationship = "101";
                userMap.children = GetMapChildren(_qRes[i].IdNavigation);
                userMapChildren.Add(userMap);
            }

            return userMapChildren;
        }
        public UserMapBO GetMap(TblUserAuth userAuth)
        {
            using ArkContext db = new ArkContext();
            UserInfoRepository userInfoRepository = new UserInfoRepository();
            UserBusinessPackageRepository userBusinessPackageRepository = new UserBusinessPackageRepository();

            TblUserInfo userInfo = userInfoRepository.Get(userAuth, db);
            List<TblUserBusinessPackage> userBusinessPackages = userBusinessPackageRepository.GetAllUserPackages(userAuth, db);
            userBusinessPackages = userBusinessPackages.FindAll(i => i.UserDepositRequest.DepositStatus == (short)DepositStatus.Paid);

            UserMapBO userMapBO = new UserMapBO
            {
                title = userBusinessPackages.Count > 0 ? @String.Format("BP ({0:#,##0.000})", userBusinessPackages.Sum(i => i.UserDepositRequest.Amount)) : "Inactive",
                name = userAuth.UserName,
                relationship = "101",
                children = GetMapChildren(userAuth)
            };

            return userMapBO;
        }
        public List<UnilevelMapBO> GetUnilevelChildren(int maxDeepness, int counter, TblUserAuth userAuth, ArkContext db)
        {
            UserIncomeTransactionRepository userIncomeTransactionRepository = new UserIncomeTransactionRepository();
            List<TblUserIncomeTransaction> tblUserIncomeTransactions = userIncomeTransactionRepository.GetAll(userAuth, db);
            var _q = from a in db.TblUserMap
                     join b in db.TblUserAuth on a.Id equals b.Id
                     join c in db.TblUserInfo on b.UserInfoId equals c.Id
                     join d in db.TblUserBusinessPackage on b.Id equals d.UserAuthId
                     join e in db.TblBusinessPackage on d.BusinessPackageId equals e.Id 

                     where a.SponsorUserId == userAuth.Id
                     orderby a.Id ascending
                     select new UnilevelMapBO
                     {
                         Text = String.Format("{0} {1} - {2} | {3} | Commissions: {4}", c.FirstName, c.LastName, d.PackageStatus == PackageStatus.PendingActivation ? "Pending Activation" : "Source Code: " + c.Uid , e.PackageName, 0m),
                         MapBO = a,
                         UserAuth = b,
                         TotalCommission = 0,//(decimal)tblUserIncomeTransactions.Where(x => x.TriggeredByUbpId == b.Id).Sum(i => i.IncomePercentage),
                         UserBusinessPackage = new TblUserBusinessPackage { BusinessPackage = e , ActivationDate = d.ActivationDate, BusinessPackageId = d.BusinessPackageId, CreatedOn = d.CreatedOn, CancellationDate = d.CancellationDate, PackageStatus = d.PackageStatus}
                     };

            List<UnilevelMapBO> _qRes = _q.ToList<UnilevelMapBO>();
            
            if (_qRes.Count != 0)
            {
                counter = counter + 1;
                if (counter <= maxDeepness)
                {
                    for (int i = 0; i < _qRes.Count; i++)
                    {
                        List<UnilevelMapBO> _p = GetUnilevelChildren(maxDeepness, counter, new TblUserAuth { Id = (long)_qRes[i].MapBO.Id }, db);
                        _qRes[i].Nodes = _p.Count > 0 ? _p : null;
                    }
                }
               
            }

            return _qRes;
        }
        public UnilevelMapBO GetUnilevel(TblUserAuth userAuth, ArkContext db)
        {
            List<UnilevelMapBO> _o = GetUnilevelChildren(3, 0, userAuth, db);
            UnilevelMapBO unilevelMapBO = new UnilevelMapBO()
            {
                Text = userAuth.UserName,
                Nodes = _o.Count > 0 ? _o : null
            };
            return unilevelMapBO;
        }

    }
}
