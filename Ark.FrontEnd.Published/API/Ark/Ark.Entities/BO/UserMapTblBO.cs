using System;
using System.Collections.Generic;
using System.Text;
using Ark.Entities.DTO;

namespace Ark.Entities.BO
{
   public class UserMapTblBO : TblUserMap
    {
        public TblUserAuth UserAuth { get; set; }
    }
}
