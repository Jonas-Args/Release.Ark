using System;
using System.Collections.Generic;
using System.Text;

namespace Ark.Entities.BO
{
   public class StructureMapInjectionKey
    {
        public int Key { get; set; }
        public string Name { get; set; }
        public int Parent { get; set; }
        public string Title { get; set; }
        public string Comments { get; set; }
    }   
}
