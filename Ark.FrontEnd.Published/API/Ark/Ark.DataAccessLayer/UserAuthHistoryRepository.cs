using System.Text;
using System.Linq;
using System.Security.Cryptography;
using Ark.Entities.DTO;
using Ark.Entities.Enums;

namespace Ark.DataAccessLayer
{
   public class UserAuthHistoryRepository
    {
        public bool Create(short authStatus, TblUserAuth userAuth, ArkContext db)
        {
            TblUserAuthHistory _userAuthHistory = new TblUserAuthHistory();
            _userAuthHistory.AuthStatus = authStatus;
            _userAuthHistory.UserAuthId = userAuth.Id;

            if (authStatus == (int)AuthStatus.Success)
            { _userAuthHistory.IsSuccess = true; }
            else { _userAuthHistory.IsSuccess = false; }


            db.TblUserAuthHistory.Add(_userAuthHistory);
            db.SaveChanges();

            return true;
        }
    }
}
