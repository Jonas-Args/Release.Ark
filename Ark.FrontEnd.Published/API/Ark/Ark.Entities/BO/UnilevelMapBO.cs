using System;
using System.Collections.Generic;
using System.Text;
using System.Globalization;
using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using Ark.Entities.DTO;

namespace Ark.Entities.BO
{
    public class UnilevelMapBO
    {
        [JsonProperty("text")]
        public string Text { get; set; }
        [JsonProperty("nodes", NullValueHandling = NullValueHandling.Ignore)]
        public List<UnilevelMapBO> Nodes { get; set; }
        [JsonProperty("mapbo"), JsonIgnore]
        public TblUserMap MapBO { get; set; }
        public decimal TotalCommission { get; set; }
        public TblUserAuth UserAuth { get; set; }
        public TblUserBusinessPackage UserBusinessPackage{ get; set; }

    }
}
