using Ark.Entities.DTO;
using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.BO
{
   public class BusinessPackagesResponseBO : ApiResponseBO
    {
        public List<TblBusinessPackage> BusinessPackages { get; set; }
    }
}
