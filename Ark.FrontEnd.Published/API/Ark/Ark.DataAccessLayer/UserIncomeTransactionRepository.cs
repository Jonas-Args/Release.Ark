using Ark.Entities.BO;
using Ark.Entities.DTO;
using Ark.Entities.Enums;
using System;
using System.Collections.Generic;
using System.Linq;

namespace Ark.DataAccessLayer
{
    public class UserIncomeTransactionRepository
    {
        public TblUserIncomeTransaction Create(UserWalletBO userWallet, WalletTransactionBO walletTransaction, TblIncomeDistribution incomeDistribution, DataAccessLayer.ArkContext db)
        {
            TblUserIncomeTransaction userIncomeTransaction = new TblUserIncomeTransaction
            {
                UserAuthId = userWallet.UserAuthId,
                CreatedOn = DateTime.Now,
                IncomeTypeId = incomeDistribution.IncomeTypeId,
                TriggeredByUbpId = long.Parse(walletTransaction.From),
                TransactionType = (short)TransactionType.Received,
                IncomeStatus = (short)TransactionStatus.Completed
            };

            db.TblUserIncomeTransaction.Add(userIncomeTransaction);
            db.SaveChanges();

            return userIncomeTransaction;

        }

        public List<TblUserIncomeTransaction> GetAll(TblUserAuth userAuth, ArkContext db)
        {
            List<TblUserIncomeTransaction> userIncomeTransactions = db.TblUserIncomeTransaction.Where(item => item.UserAuthId == userAuth.Id).ToList();
            return userIncomeTransactions;
        }
    }
}
