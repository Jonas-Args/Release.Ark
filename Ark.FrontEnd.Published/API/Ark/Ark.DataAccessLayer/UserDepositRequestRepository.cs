using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using AutoMapper;
using System;
using System.Collections.Generic;

namespace Ark.DataAccessLayer
{
   public class UserDepositRequestRepository
    {
        public TblUserDepositRequest Create(TblUserDepositRequest tblUserDepositRequest,ArkContext db)
        {
            db.TblUserDepositRequest.Add(tblUserDepositRequest);
            db.SaveChanges();

            return tblUserDepositRequest;
        }

        public TblUserDepositRequest Get(TblUserDepositRequest tblUserDepositRequest, ArkContext db)
        {
            TblUserDepositRequest userDepositRequest = db.TblUserDepositRequest.FirstOrDefault(item => item.Id == tblUserDepositRequest.Id);
            return userDepositRequest;
        }
        public void Update(TblUserDepositRequest tblUserDepositRequest, ArkContext db)
        {
            db.TblUserDepositRequest.Update(tblUserDepositRequest);
            db.SaveChanges();
        }
        public List<TblUserDepositRequest> GetAll(TblUserAuth userAuth, DepositStatus depositStatus, ArkContext db = null)
        {
            List<TblUserDepositRequest> userDepositRequests = db.TblUserDepositRequest.Where(i => i.UserAuthId == userAuth.Id && i.DepositStatus == (short)depositStatus).ToList();
            return userDepositRequests;
        }
    }
}
