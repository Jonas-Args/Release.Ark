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
   public class UserWalletAddressRepository
    {
        public bool Create(TblUserWalletAddress tblUserWalletAddress, ArkContext db)
        {
            db.TblUserWalletAddress.Add(tblUserWalletAddress);
            db.SaveChanges();
            return true;
        }

        public List<TblUserWalletAddress> GetAll(TblUserAuth userAuth, ArkContext db)
        {
            var _q = from a in db.TblUserWalletAddress
                     join b in db.TblWalletType on a.WalletTypeId equals b.Id
                     where a.UserAuthId == userAuth.Id
                     select new TblUserWalletAddress
                     {
                         Id = a.Id,
                         Address = a.Address,
                         CreatedOn = a.CreatedOn,
                         UserAuthId = a.UserAuthId,
                         Balance = a.Balance,
                         Remarks = a.Remarks,
                         IsEnabled = a.IsEnabled,
                         WalletType = b
                     };

            List<TblUserWalletAddress> _r = _q.ToList<TblUserWalletAddress>();

            return _r;
        }

        public bool Update(TblUserWalletAddress tblUserWalletAddress, ArkContext db)
        {
            db.TblUserWalletAddress.Update(tblUserWalletAddress);
            db.SaveChanges();

            tblUserWalletAddress.WalletType.TblUserWalletAddress = new List<TblUserWalletAddress>();
            tblUserWalletAddress.WalletType.TblUserWallet = new List<TblUserWallet>();
            return true;
        }
    }
}
