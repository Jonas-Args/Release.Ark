using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.ExternalUtilities.Models
{
   public class BlockchainApiSettings : GenericApiSettings
    {
        public string XpubKey { get; set; }
        public Uri BlockCypherApiUri { get; set; }
        public string WalletID { get; set; }
        public string WalletPassword { get; set; }
    }
}
