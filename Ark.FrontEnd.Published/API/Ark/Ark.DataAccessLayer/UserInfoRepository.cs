using System.Linq;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using System;
using System.Collections.Generic;
using Ark.ExternalUtilities;

namespace Ark.DataAccessLayer
{
    public class UserInfoRepository
    {
        public TblUserInfo Create(UserBO userBO, ArkContext db)
        {
            TblUserInfo _userInfo = new TblUserInfo();
            SeedString seedString = new SeedString();
            Guid g = Guid.NewGuid();

            string r = String.Format("{0}{1}{2}", userBO.FirstName.Substring(0,1), userBO.LastName.Substring(0,1), seedString.GenerateRandom(8));

            _userInfo.FirstName = userBO.FirstName;
            _userInfo.LastName = userBO.LastName;
            _userInfo.PhoneNumber = userBO.PhoneNumber;
            _userInfo.Email = userBO.Email;
            _userInfo.Dob = userBO.Dob;
            _userInfo.CountryIsoCode2 = userBO.CountryIsoCode2;
            _userInfo.Gender = userBO.Gender;
            _userInfo.Uid = r;//g.ToString();
            _userInfo.IsEnabled = true;
            _userInfo.EmailStatus = (short)EmailStatus.Unverified;

            db.TblUserInfo.Add(_userInfo);
            db.SaveChanges();

            return _userInfo;
        }

        public TblUserInfo Get(TblUserAuth userAuth, ArkContext db)
        {
            var _qUi = from a in db.TblUserInfo
                       join b in db.TblUserAuth on a.Id equals b.UserInfoId
                       where a.Id == userAuth.UserInfoId || b.UserName == userAuth.UserName
                       select new TblUserInfo
                       {
                           FirstName = a.FirstName,
                           LastName = a.LastName,
                           Dob = a.Dob,
                           Email = a.Email,
                           PhoneNumber = a.PhoneNumber,
                           Gender = a.Gender,
                           Uid = a.Uid,
                           EmailStatus = a.EmailStatus,
                           CreatedOn = a.CreatedOn,
                           CountryIsoCode2 = a.CountryIsoCode2,
                           CompanyName = a.CompanyName

                       };

            TblUserInfo _tblUserInfo = _qUi.FirstOrDefault();
            return _tblUserInfo;
        }

        public List<UserBO> GetAll(ArkContext db)
        {
            var _qUi = from a in db.TblUserInfo
                       join b in db.TblUserAuth on a.Id equals b.UserInfoId
                       join c in db.TblUserBusinessPackage on b.Id equals c.UserAuthId into ubp
                       from c in ubp.DefaultIfEmpty()

                       join d in db.TblBusinessPackage on c.BusinessPackageId equals d.Id into bp
                       from _bp in bp.DefaultIfEmpty()

                       join e in db.TblUserDepositRequest on c.UserDepositRequestId equals e.Id into udr
                       from _udr in udr.DefaultIfEmpty()
                       //where a.Id == userAuth.UserInfoId || b.UserName == userAuth.UserName
                       select new UserBO
                       {
                           FirstName = a.FirstName,
                           LastName = a.LastName,
                           Dob = a.Dob,
                           Email = a.Email,
                           PhoneNumber = a.PhoneNumber,
                           Gender = a.Gender,
                           Uid = a.Uid,
                           EmailStatus = a.EmailStatus,
                           CreatedOn = a.CreatedOn,
                           CountryIsoCode2 = a.CountryIsoCode2,
                           CompanyName = a.CompanyName,
                           UserName = b.UserName,
                           UserBusinessPackage = new TblUserBusinessPackage { ActivationDate = c.ActivationDate, CreatedOn = c.CreatedOn, PackageStatus = c.PackageStatus, UserDepositRequest = _udr, BusinessPackage = _bp }
                       };

            List<UserBO> _users = _qUi.ToList();
            return _users;
        }

        public List<UserBO> GetAllDeposit(ArkContext db)
        {
            var _qUi = from a in db.TblUserInfo
                       join b in db.TblUserAuth on a.Id equals b.UserInfoId
                       join c in db.TblUserBusinessPackage on b.Id equals c.UserAuthId
                       join d in db.TblBusinessPackage on c.BusinessPackageId equals d.Id
                       join e in db.TblUserDepositRequest on c.UserDepositRequestId equals e.Id 

                       where e.DepositStatus == (int)DepositStatus.PendingPayment
                       select new UserBO
                       {
                           FirstName = a.FirstName,
                           LastName = a.LastName,
                           Dob = a.Dob,
                           Email = a.Email,
                           PhoneNumber = a.PhoneNumber,
                           Gender = a.Gender,
                           Uid = a.Uid,
                           EmailStatus = a.EmailStatus,
                           CreatedOn = a.CreatedOn,
                           CountryIsoCode2 = a.CountryIsoCode2,
                           CompanyName = a.CompanyName,
                           UserName = b.UserName,
                           UserBusinessPackage = new TblUserBusinessPackage { Id = c.Id, ActivationDate = c.ActivationDate, CreatedOn = c.CreatedOn, PackageStatus = c.PackageStatus, UserDepositRequest = e, BusinessPackage = d }
                       };

            List<UserBO> _users = _qUi.ToList();
            return _users;
        }

        public bool VerifyEmail(TblUserAuth userAuth, ArkContext db)
        {
            TblUserInfo userInfo_1 = db.TblUserInfo.SingleOrDefault(i => i.Email == userAuth.UserName);
            userInfo_1.EmailStatus = (short)EmailStatus.Verified;

            db.SaveChanges();

            return true;
        }
    }
}
