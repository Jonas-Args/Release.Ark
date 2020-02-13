using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;

namespace Ark.DataAccessLayer
{
   public class UserRoleRepository
    {
        public TblUserRole Get(TblUserAuth userAuth, ArkContext db)
        {
            var _qObj= from a in db.TblUserRole
                         where a.UserAuthId == userAuth.Id
                         select new TblUserRole
                         {
                             AccessRole = a.AccessRole,
                             IsEnabled = a.IsEnabled,
                             CreatedOn = a.CreatedOn,
                             Id = a.Id
                         };

            TblUserRole userRole = _qObj.FirstOrDefault();

            return userRole;
        }

        public TblUserRole Create(TblUserAuth userAuth, ArkContext db)
        {
            TblUserRole userRole = new TblUserRole();
            userRole.UserAuthId = userAuth.Id;
            userRole.IsEnabled = true;
            userRole.AccessRole = UserRole.Client.ToString();
            
            db.TblUserRole.Add(userRole);
            db.SaveChanges();

            return userRole;
        }
    }
}
