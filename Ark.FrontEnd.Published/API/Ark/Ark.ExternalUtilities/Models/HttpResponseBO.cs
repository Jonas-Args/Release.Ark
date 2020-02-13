using System;
using System.Collections.Generic;
using System.Net;
using System.Text;

namespace Ark.ExternalUtilities.Models
{
   public class HttpResponseBO
    {
        public CookieCollection ResponseCookies { get; set; }

        public string ResponseResult { get; set; }
    }
}
