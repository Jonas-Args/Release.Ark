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
                IncomeStatus = (short)TransactionStatus.Completed,
                IncomePercentage = walletTransaction.Amount,
                Remarks = walletTransaction.Remarks
            };

            db.TblUserIncomeTransaction.Add(userIncomeTransaction);
            db.SaveChanges();

            return userIncomeTransaction;

        }

        public List<TblUserIncomeTransaction> GetAll(TblUserAuth userAuth, ArkContext db)
        {
              var _qObj = from a in db.TblUserIncomeTransaction
                          join b in db.TblUserAuth on a.UserAuthId equals b.Id

                          join d in db.TblUserAuth on a.TriggeredByUbpId equals d.Id

                          where a.UserAuthId == userAuth.Id
                          select new TblUserIncomeTransaction
                          {
                            Id = a.Id,
                            CreatedOn = a.CreatedOn,
                            UserAuth = d,
                            IncomePercentage = a.IncomePercentage,
                            IncomeTypeId = a.IncomeTypeId,
                            TriggeredByUbpId = a.TriggeredByUbpId,
                            TransactionType = a.TransactionType,
                            IncomeStatus = a.IncomeStatus,
                            Remarks = a.Remarks                            
                          };

            List<TblUserIncomeTransaction> userIncomeTransactions = _qObj.ToList();

            return userIncomeTransactions;
        }
    }
}
