using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.ExternalUtilities.Models
{
    public class GenericApiSettings
    {
        public string ApiKey { get; set; }
        public Uri ApiUri { get; set; }
        public string ServiceUrl { get; set; }
        public string CallbackURL { get; set; }
    }
}
