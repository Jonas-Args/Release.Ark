using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum AffiliateBonusType : long
    {
        AffiliateProfits = 1,
        BinaryProfits = 2,
        DailyProfits = 4
    }
}
