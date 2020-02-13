using System;
using System.Collections.Generic;
using System.Text;
using Ark.Entities.DTO;

namespace Ark.Entities.BO
{
   public class ExchangeRateBO : TblExchangeRate
    {
        public decimal? OppositeValue { get => 1 / Value;}
    }
}
