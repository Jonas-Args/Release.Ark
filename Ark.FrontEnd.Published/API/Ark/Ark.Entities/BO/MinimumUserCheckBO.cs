using Ark.Entities.DTO;
using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.BO
{
    public class MinimumUserCheckBO
    {
        public List<TblUserBusinessPackage> userBusinessPackages { get; set; }
        public bool IsPassed { get; set; }
    }
}
