using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using System.Collections.Generic;

namespace Ark.AppService
{
    public class UserWalletAppService
    {
        public bool Create(TblUserAuth tblUserAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletRepository userWalletRepository = new UserWalletRepository();
                return userWalletRepository.Create(tblUserAuth, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletRepository userWalletRepository = new UserWalletRepository();
                        transaction.Commit();

                        return userWalletRepository.Create(tblUserAuth, db);
                    }
                }
            }

        }
        public List<TblUserWallet> Get(TblUserAuth tblUserAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletRepository userWalletRepository = new UserWalletRepository();
                return userWalletRepository.GetAll(tblUserAuth, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletRepository userWalletRepository = new UserWalletRepository();
                        return userWalletRepository.GetAll(tblUserAuth, db);
                    }
                }
            }

        }
        public TblUserWallet GetSingle(TblUserAuth tblUserAuth, TblWalletType walletType, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletBO userBO = new UserWalletBO();
                userBO.UserAuthId = tblUserAuth.Id;
                userBO.WalletTypeId = walletType.Id;

                UserWalletRepository userWalletRepository = new UserWalletRepository();
                return userWalletRepository.Get(userBO, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletBO userBO = new UserWalletBO();
                        userBO.UserAuthId = tblUserAuth.Id;
                        userBO.WalletTypeId = walletType.Id;

                        UserWalletRepository userWalletRepository = new UserWalletRepository();
                        return userWalletRepository.Get(userBO, db);
                    }
                }
            }
        }
        public List<UserWalletBO> GetAllBO(TblUserAuth tblUserAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletRepository userWalletRepository = new UserWalletRepository();
                UserWalletAddressAppService userWalletAddressAppService = new UserWalletAddressAppService();
                userWalletAddressAppService.UpdateBalance(tblUserAuth, db);
                return userWalletRepository.GetAllBO(tblUserAuth, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletRepository userWalletRepository = new UserWalletRepository();
                        UserWalletAddressAppService userWalletAddressAppService = new UserWalletAddressAppService();
                        userWalletAddressAppService.UpdateBalance(tblUserAuth, db);
                        return userWalletRepository.GetAllBO(tblUserAuth, db);
                    }
                }
            }

        }
        public UserWalletBO GetBO(UserWalletBO userWallet, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletRepository userWalletRepository = new UserWalletRepository();
                return userWalletRepository.GetBO(userWallet, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletRepository userWalletRepository = new UserWalletRepository();
                        return userWalletRepository.GetBO(userWallet, db);
                    }
                }
            }

        }
        public List<TblUserWalletTransaction> GetAllTransactions(TblUserAuth tblUserAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserWalletTransactionRepository userWalletTransactionRepository = new UserWalletTransactionRepository();
                return userWalletTransactionRepository.GetAll(tblUserAuth, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserWalletTransactionRepository userWalletTransactionRepository = new UserWalletTransactionRepository();
                        return userWalletTransactionRepository.GetAll(tblUserAuth, db);
                    }
                }
            }

        }
        public bool Increment(UserWalletBO userWallet, WalletTransactionBO walletTransaction, DataAccessLayer.ArkContext db = null)
        {
            UserWalletRepository userWalletRepository = new UserWalletRepository();

            if (db != null)
            {
                TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                UserWalletBO targetUserWallet = new UserWalletBO();

                targetUserWallet.UserAuthId = userWallet.UserAuthId;
                targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                targetUserWallet.Balance = sourceUserWallet.Balance + (decimal)walletTransaction.Amount;

                return userWalletRepository.Update(targetUserWallet, db);

            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                        UserWalletBO targetUserWallet = new UserWalletBO();

                        targetUserWallet.UserAuthId = userWallet.UserAuthId;
                        targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                        targetUserWallet.Balance = sourceUserWallet.Balance + (decimal)walletTransaction.Amount;

                        bool res = userWalletRepository.Update(targetUserWallet, db);
                        transaction.Commit();

                        return res;
                    }
                }
            }

        }
        public bool Decrement(UserWalletBO userWallet, WalletTransactionBO walletTransaction, DataAccessLayer.ArkContext db = null)
        {
            UserWalletRepository userWalletRepository = new UserWalletRepository();

            if (db != null)
            {
                TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                UserWalletBO targetUserWallet = new UserWalletBO();

                targetUserWallet.UserAuthId = userWallet.UserAuthId;
                targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                targetUserWallet.Balance = sourceUserWallet.Balance - (decimal)walletTransaction.Amount;

                return userWalletRepository.Update(targetUserWallet, db);

            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                        UserWalletBO targetUserWallet = new UserWalletBO();

                        targetUserWallet.UserAuthId = userWallet.UserAuthId;
                        targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                        targetUserWallet.Balance = sourceUserWallet.Balance - (decimal)walletTransaction.Amount;

                        bool res = userWalletRepository.Update(targetUserWallet, db);
                        transaction.Commit();

                        return res;
                    }
                }
            }
        }
        public bool Adjust(UserWalletBO userWallet, WalletTransactionBO walletTransaction, DataAccessLayer.ArkContext db = null)
        {
            UserWalletRepository userWalletRepository = new UserWalletRepository();

            if (db != null)
            {
                TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                UserWalletBO targetUserWallet = new UserWalletBO();

                targetUserWallet.UserAuthId = userWallet.UserAuthId;
                targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                targetUserWallet.Balance = (decimal)walletTransaction.Amount;

                return userWalletRepository.Update(targetUserWallet, db);

            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        TblUserWallet sourceUserWallet = userWalletRepository.Get(userWallet, db);
                        UserWalletBO targetUserWallet = new UserWalletBO();

                        targetUserWallet.UserAuthId = userWallet.UserAuthId;
                        targetUserWallet.WalletTypeId = userWallet.WalletTypeId;
                        targetUserWallet.Balance = (decimal)walletTransaction.Amount;

                        bool res = userWalletRepository.Update(targetUserWallet, db);
                        transaction.Commit();

                        return res;
                    }
                }
            }
        }
        public bool Transfer(UserWalletBO sourceUserWalletBO, UserWalletBO targetUserWalletBO, WalletTransactionBO walletTransaction, DataAccessLayer.ArkContext db = null)
        {
            UserWalletRepository userWalletRepository = new UserWalletRepository();
            ExchangeRateRepository exchangeRateRepository = new ExchangeRateRepository();

            if (db != null)
            {
                TblUserWallet sourceUserWallet = userWalletRepository.Get(sourceUserWalletBO, db);
                TblUserWallet targetUserWallet = userWalletRepository.Get(targetUserWalletBO, db);


                TblExchangeRate exchangeRate = new TblExchangeRate();
                exchangeRate.SourceCurrencyId = (long)sourceUserWallet.WalletType.CurrencyId;
                exchangeRate.TargetCurrencyId = (long)targetUserWallet.WalletType.CurrencyId;
                exchangeRate = exchangeRateRepository.Get(exchangeRate, db);

                decimal? topUpValue = (walletTransaction.Amount * exchangeRate.Value);
                decimal? fee;

                if (walletTransaction.IsFeeEnabled == true)
                {
                    fee = topUpValue * exchangeRate.Fee; // Percentage Fee
                }
                else
                {
                    fee = 0m;
                }
                topUpValue = topUpValue - fee;

                UserWalletBO sourceUserWalletUpdate = new UserWalletBO();
                sourceUserWalletUpdate.UserAuthId = sourceUserWallet.UserAuthId;
                sourceUserWalletUpdate.WalletTypeId = sourceUserWallet.WalletTypeId;
                sourceUserWalletUpdate.Balance = (decimal)sourceUserWallet.Balance - topUpValue;

                bool a = userWalletRepository.Update(sourceUserWalletUpdate, db);

                if (a == false)
                {
                    return false;
                }
                else
                {
                    UserWalletBO targetUserWalletUpdate = new UserWalletBO();
                    targetUserWalletUpdate.UserAuthId = targetUserWallet.UserAuthId;
                    targetUserWalletUpdate.WalletTypeId = targetUserWallet.WalletTypeId;
                    targetUserWalletUpdate.Balance = (decimal)targetUserWallet.Balance + topUpValue;

                    return userWalletRepository.Update(targetUserWalletUpdate, db);
                }
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        TblUserWallet sourceUserWallet = userWalletRepository.Get(sourceUserWalletBO, db);
                        TblUserWallet targetUserWallet = userWalletRepository.Get(targetUserWalletBO, db);


                        TblExchangeRate exchangeRate = new TblExchangeRate();
                        exchangeRate.SourceCurrencyId = (long)sourceUserWallet.WalletType.CurrencyId;
                        exchangeRate.TargetCurrencyId = (long)targetUserWallet.WalletType.CurrencyId;
                        exchangeRate = exchangeRateRepository.Get(exchangeRate, db);

                        decimal? topUpValue = (walletTransaction.Amount * exchangeRate.Value);
                        decimal? fee;

                        if (walletTransaction.IsFeeEnabled == true)
                        {
                            fee = topUpValue * exchangeRate.Fee; // Percentage Fee
                        }
                        else
                        {
                            fee = 0m;
                        }
                        topUpValue = topUpValue - fee;

                        UserWalletBO sourceUserWalletUpdate = new UserWalletBO();
                        sourceUserWalletUpdate.UserAuthId = sourceUserWallet.UserAuthId;
                        sourceUserWalletUpdate.WalletTypeId = sourceUserWallet.WalletTypeId;
                        sourceUserWalletUpdate.Balance = (decimal)sourceUserWallet.Balance - topUpValue;

                        bool a = userWalletRepository.Update(sourceUserWalletUpdate, db);

                        if (a == false)
                        {
                            return false;
                        }
                        else
                        {
                            UserWalletBO targetUserWalletUpdate = new UserWalletBO();
                            targetUserWalletUpdate.UserAuthId = targetUserWallet.UserAuthId;
                            targetUserWalletUpdate.WalletTypeId = targetUserWallet.WalletTypeId;
                            targetUserWalletUpdate.Balance = (decimal)targetUserWallet.Balance + topUpValue;

                            transaction.Commit();

                            return userWalletRepository.Update(targetUserWalletUpdate, db);
                        }
                    }
                }
            }

        }

    }
}
