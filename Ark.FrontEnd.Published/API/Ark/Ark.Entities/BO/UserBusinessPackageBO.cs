using System;
using System.Collections.Generic;
using System.Text;
using Ark.Entities.DTO;
using Ark.Entities.Enums;

namespace Ark.Entities.BO
{
   public class UserBusinessPackageBO : TblUserAuth
    {
        public long UserPackageID { get; set; }
        public string FromCurrencyIso3{ get; set; }
        public string FromWalletCode { get; set; }
        public decimal AmountPaid { get; set; }
        public string PaymentAddress { get; set; }
        public long BusinessPackageID { get; set; }
        public string Remarks { get; set; }
        public DepositStatus DepositStatus { get; set; }
    }
}
