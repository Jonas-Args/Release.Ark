using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using System.Collections.Generic;
using System;
using System.Threading.Tasks;

namespace Ark.AppService
{
   public class AdminAccessAppService
    {
        public List<UserBO> GetAllUsers(DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserInfoRepository userInfoRepository = new UserInfoRepository();
                return userInfoRepository.GetAll(db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserInfoRepository userInfoRepository = new UserInfoRepository();
                        return userInfoRepository.GetAll(db);
                    }
                }
            }
        }

        public List<UserBO> GetAllDepositRequest(DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserInfoRepository userInfoRepository = new UserInfoRepository();
                return userInfoRepository.GetAllDeposit(db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using (var transaction = db.Database.BeginTransaction())
                    {
                        UserInfoRepository userInfoRepository = new UserInfoRepository();
                        return userInfoRepository.GetAllDeposit(db);
                    }
                }
            }
        }
    }
}
