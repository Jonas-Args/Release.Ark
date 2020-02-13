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
   public class CurrencyTypeRepository
    {
        public TblCurrency Get(TblCurrency currency, ArkContext db)
        {
            TblCurrency currencyType = db.TblCurrency.FirstOrDefault(item => item.Id == currency.Id || item.CurrencyIsoCode3 == currency.CurrencyIsoCode3);
            return currencyType;
        }
    }
}
