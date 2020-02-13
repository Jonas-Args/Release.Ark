using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum WalletTypeID : int
    {
        ArkCash = 18,
        ArkCredits = 20,
    }
}
