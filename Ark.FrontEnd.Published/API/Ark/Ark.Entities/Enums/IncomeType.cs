using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum IncomeType: int
    {
        DSI = 0,
        USI = 1,
        MSI = 2,
        TMI = 3,
        GSI = 4,
        PSI = 5,
        PSR = 6
    }
}
