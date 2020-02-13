using System;
using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using AutoMapper;

namespace Ark.DataAccessLayer
{
   public class GenericRepository
    {
        //public readonly Mapper _mapper = new Mapper();

        public TblAuditFields GenericInjection()
        {
            TblAuditFields data = new TblAuditFields();

            data.IsEnabled = true;
            data.CreatedOn = DateTime.Now;
            data.CreatedBy = 1;
            data.ModifiedOn = DateTime.Now;
            data.ModifiedBy = 1;
            data.LastChanged = DateTime.Now;

            return data;
        }
    }
}
