using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum WalletType : int
    {
        CurrencyValue = 1,
        NetworkCounter = 2,
        VirtualValue = 3
    }
}
