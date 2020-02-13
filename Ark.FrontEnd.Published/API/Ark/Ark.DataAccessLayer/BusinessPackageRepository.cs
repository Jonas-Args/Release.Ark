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
   public class BusinessPackageRepository
    {
        public TblBusinessPackage Get(TblBusinessPackage businessPackageQuery, ArkContext db)
        {
            TblBusinessPackage businessPackage = db.TblBusinessPackage.FirstOrDefault(item => item.Id == businessPackageQuery.Id || item.PackageCode == businessPackageQuery.PackageCode);
            return businessPackage;
        }
        public List<TblIncomeType> GetIncomeTypes(TblBusinessPackage businessPackageQuery, ArkContext db)
        {
            var _q = from a in db.TblIncomeType
                     join b in db.TblIncomeDistribution on a.Id equals b.IncomeTypeId

                     orderby b.CreatedOn descending

                     where b.BusinessPackageId == businessPackageQuery.Id && b.IsEnabled == true
                     select new TblIncomeType
                     {
                         Id = b.Id,
                         CreatedOn = a.CreatedOn,
                         IncomePercentage = a.IncomePercentage,
                         IncomeTypeShortName = a.IncomeTypeShortName,
                         IncomeTypeCode  = a.IncomeTypeCode,
                         IncomeTypeDescription = a.IncomeTypeDescription,
                         IncomeTypeName = a.IncomeTypeName,
                         IsReward = a.IsReward,
                         IsEnabled = a.IsEnabled
                     };

            List<TblIncomeType> incomeTypes = _q.ToList<TblIncomeType>();

            return incomeTypes;
        }
    }
}
