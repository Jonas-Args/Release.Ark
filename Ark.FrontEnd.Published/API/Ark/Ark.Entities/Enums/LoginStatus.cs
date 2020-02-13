using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
   public enum LoginStatus : short
    {
        Disabled = 0,
        Enabled = 1,
        Restricted = 2,
        Blocked = 3
    }
}
