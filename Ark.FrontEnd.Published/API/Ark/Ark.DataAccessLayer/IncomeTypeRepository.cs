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
   public class IncomeTypeRepository
    {
        public bool Create()
        {
            throw new NotImplementedException();
        }
        public TblIncomeType Get(TblIncomeType incomeTypeQuery, ArkContext db)
        {
            TblIncomeType incomeType = db.TblIncomeType.FirstOrDefault(item => item.Id == incomeTypeQuery.Id || item.IncomeTypeShortName == incomeTypeQuery.IncomeTypeShortName);
            return incomeType;
        }

        public TblIncomeDistribution GetDistribution(IncomeType incomeType, TblBusinessPackage businessPackage, ArkContext db)
        {
            TblIncomeDistribution incomeDistribution = db.TblIncomeDistribution.FirstOrDefault(item => item.IncomeType.IncomeTypeCode == incomeType && item.BusinessPackageId == businessPackage.Id);
            return incomeDistribution;
        }
    }
}
