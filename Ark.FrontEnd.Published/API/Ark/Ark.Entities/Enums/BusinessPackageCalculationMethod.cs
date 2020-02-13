using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.Enums
{
    [Flags]
    public enum BusinessPackageCalculationMethod : int
    {
        NetworkValue = 0,
        PaymentValue = 1
    }
}
