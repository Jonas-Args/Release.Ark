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
   public class WalletTypeRepository
    {
        public bool Create()
        {
            return true;
        }

        public TblWalletType Get(UserWalletBO walletBO,ArkContext db)
        {
            var _q = from a in db.TblWalletType
                     where a.Id == (int)walletBO.WalletTypeId || a.Code == walletBO.WalletCode
                     select new TblWalletType
                     {
                         Name = a.Name,
                         Desc = a.Desc,
                         Type = a.Type,
                         Code = a.Code,
                         Id = a.Id,
                         CurrencyId = a.CurrencyId
                     };

            TblWalletType _qWalletTypeRes = _q.FirstOrDefault<TblWalletType>();

            return _qWalletTypeRes;
        }
        public List<TblWalletType> GetAll(ArkContext db)
        {
            var _q = from a in db.TblWalletType
                     join b in db.TblCurrency on a.CurrencyId equals b.Id
                     //where a.Id == (int)walletBO.WalletTypeId
                     select new TblWalletType
                     {
                         Name = a.Name,
                         Desc = a.Desc,
                         Type = a.Type,
                         Code = a.Code,
                         Id = a.Id,
                         CurrencyId = a.CurrencyId,
                         Currency = b

                     };

            List<TblWalletType> _qWalletTypeRes = _q.ToList<TblWalletType>();

            return _qWalletTypeRes;
        }


    }
}
