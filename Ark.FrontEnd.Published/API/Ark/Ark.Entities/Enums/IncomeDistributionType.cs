using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum IncomeDistributionType: int
    {
        Fixed = 0,
        Percentage = 1
    }
}
