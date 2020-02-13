using System;
using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using Ark.ExternalUtilities;
using Ark.ExternalUtilities.Models;
using System.Threading.Tasks;
using System.Collections.Generic;

namespace Ark.AppService
{
   public class UserWalletAddressAppService
    {
        public async Task<bool> Create(TblUserAuth userAuth, string walletCode, string appendAddress = null, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                WalletTypeRepository walletTypeRepository = new WalletTypeRepository();
                TblUserWalletAddress userWalletAddress = new TblUserWalletAddress();

                TblWalletType walletType = walletTypeRepository.Get(new UserWalletBO { WalletCode = walletCode, WalletTypeId = 0 }, db);

                if (appendAddress == null)
                {
                    Blockchain blockchain = new Blockchain();
                    BlockchainResponse _br = await blockchain.NewPaymentAddress("").ConfigureAwait(true);
                    userWalletAddress.Address = _br.Address;
                }
                else
                {
                    userWalletAddress.Address = appendAddress;
                }
                userWalletAddress.UserAuthId = userAuth.Id;
                userWalletAddress.Balance = 0m;
                userWalletAddress.WalletTypeId = walletType.Id;

                userWalletAddressRepository.Create(userWalletAddress, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                        WalletTypeRepository walletTypeRepository = new WalletTypeRepository();
                        TblUserWalletAddress userWalletAddress = new TblUserWalletAddress();

                        TblWalletType walletType = walletTypeRepository.Get(new UserWalletBO { WalletCode = walletCode, WalletTypeId = 0 }, db);

                        if (appendAddress == null)
                        {
                            Blockchain blockchain = new Blockchain();
                            BlockchainResponse _br = await blockchain.NewPaymentAddress("").ConfigureAwait(true);
                            userWalletAddress.Address = _br.Address;
                        }
                        else
                        {
                            userWalletAddress.Address = appendAddress;
                        }
                        userWalletAddress.UserAuthId = userAuth.Id;
                        userWalletAddress.Balance = 0m;
                        userWalletAddress.WalletTypeId = walletType.Id;

                        userWalletAddressRepository.Create(userWalletAddress, db);

                        transaction.Commit();
                    }
                }
            }

            return true;
        }
        public List<TblUserWalletAddress> GetAll(TblUserAuth userAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                List<TblUserWalletAddress> _r = userWalletAddressRepository.GetAll(userAuth, db);
                _UpdateBalance(_r, new TblWalletType {Code = "BTC" }, db);

                return _r;
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                        List<TblUserWalletAddress> _r = userWalletAddressRepository.GetAll(userAuth, db);
                        _UpdateBalance(_r, new TblWalletType { Code = "BTC" }, db);

                        transaction.Commit();
                        return _r;
                    }
                }
            }
        }
        private bool _UpdateBalance(List<TblUserWalletAddress> userWalletAddresses, TblWalletType walletType, DataAccessLayer.ArkContext db)
        {

            UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
            UserWalletAppService userWalletAppService = new UserWalletAppService();
            Blockchain blockchain = new Blockchain();
            BlockchainTx _br;
            decimal _bal;

            List<TblUserWalletAddress> _btcUserWalletAddresses = userWalletAddresses.FindAll(i => i.WalletType.Code == walletType.Code);

            foreach (var item in _btcUserWalletAddresses)
            {
                _br = blockchain.GetAddressTransactions(item.Address).Result;
                _bal = (_br.Txrefs.Sum(i => i.Value) / 100000000m);
                
                if (_bal != item.Balance)
                {
                    item.Balance = _bal;
                    userWalletAppService.Adjust(new UserWalletBO { UserAuthId = item.UserAuthId, WalletTypeId = item.WalletType.Id}, new WalletTransactionBO { Amount = _bal }, db);
                    userWalletAddressRepository.Update(item, db);
                }               
                
            }

            return true;
        }
        public bool UpdateBalance(TblUserAuth userAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                List<TblUserWalletAddress> _r = userWalletAddressRepository.GetAll(userAuth, db);
                _UpdateBalance(_r, new TblWalletType { Code = "BTC" }, db);

                return true;
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletAddressRepository userWalletAddressRepository = new UserWalletAddressRepository();
                        List<TblUserWalletAddress> _r = userWalletAddressRepository.GetAll(userAuth, db);
                        _UpdateBalance(_r, new TblWalletType { Code = "BTC" }, db);

                        transaction.Commit();
                        return true;
                    }
                }
            }
        }
    }
}
