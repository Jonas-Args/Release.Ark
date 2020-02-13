using System.Collections.Generic;
using Ark.Entities.DTO;

namespace Ark.Entities.BO
{
    public class UserResponseBO : ApiResponseBO
    {
        public TblUserAuth UserAuth { get; set; }
        public TblUserInfo UserInfo { get; set; }
        public TblUserRole UserRole { get; set; }
        public UserMapBO UserBinaryMap { get; set; }
        public UnilevelMapBO UserUnilevelMap { get; set; }
        public List<TblUserBusinessPackage> BusinessPackages { get; set; }
        public List<UserWalletBO> UserWallet { get; set;}
        public List<TblUserWalletTransaction> UserWalletTransactions { get; set; }
        public List<TblUserWalletAddress> UserWalletAddress { get; set; }
        public List<TblUserIncomeTransaction> UserIncomeTransactions { get; set; }
        public List<TblUserDepositRequest> UserDepositRequests { get; set; }
    }
}
