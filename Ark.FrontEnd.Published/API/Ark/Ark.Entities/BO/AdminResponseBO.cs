using Ark.Entities.DTO;
using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.BO
{
   public class AdminResponseBO : ApiResponseBO
    {
        public List<UserBO> UserList { get; set; }
        public List<UserBO> UserDepositRequests { get; set; }
    }
}
