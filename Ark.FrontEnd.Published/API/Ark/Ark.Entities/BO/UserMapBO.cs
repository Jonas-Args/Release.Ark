using System;
using System.Collections.Generic;
using System.Text;
using Ark.Entities.DTO;

namespace Ark.Entities.BO
{
   public class UserMapBO
    {
        public TblUserAuth UserAuth { get; set; }
        public string name { get; set; }
        public string title { get; set; }
        public List<UserMapBO> children { get; set; }
        public string relationship { get; set; }

    }
}
