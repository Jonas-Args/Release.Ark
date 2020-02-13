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
    public class UserBusinessPackageRepository
    {
        public bool Create(TblUserBusinessPackage tblUserBusinessPackage, ArkContext db)
        {
            db.TblUserBusinessPackage.Add(tblUserBusinessPackage);
            db.SaveChanges();

            return true;
        }

        public TblUserBusinessPackage Get(TblUserBusinessPackage tblUserBusinessPackage, ArkContext db)
        {
            TblUserBusinessPackage userBusinessPackage = db.TblUserBusinessPackage.FirstOrDefault(item => item.Id == tblUserBusinessPackage.Id);
            //return userBusinessPackage;

            var _qUi = from a in db.TblUserBusinessPackage
                       join b in db.TblBusinessPackage on a.BusinessPackageId equals b.Id
                       join c in db.TblUserDepositRequest on a.UserDepositRequestId equals c.Id

                       where a.IsEnabled == true && a.Id == tblUserBusinessPackage.Id

                       orderby a.CreatedOn descending
                       select new TblUserBusinessPackage
                       {
                           Id = a.Id,
                           CreatedOn = a.CreatedOn,
                           IsEnabled = a.IsEnabled,
                           ActivationDate = a.ActivationDate,
                           BusinessPackage = b,
                           BusinessPackageId = a.BusinessPackageId,
                           CancellationDate = a.CancellationDate,
                           CreatedBy = a.CreatedBy,
                           ExpiryDate = a.ExpiryDate,
                           UserDepositRequestId = a.UserDepositRequestId,
                           UserDepositRequest = c,
                           PackageStatus = a.PackageStatus,
                           ModifiedOn = a.ModifiedOn,
                           UserAuthId = a.UserAuthId
                       };

            TblUserBusinessPackage _ubp = _qUi.FirstOrDefault();

            return _ubp;
        }
        public void Update(TblUserBusinessPackage tblUserBusinessPackage, ArkContext db)
        {
            db.TblUserBusinessPackage.Update(tblUserBusinessPackage);
            db.SaveChanges();
        }

        public List<TblUserBusinessPackage> GetAllUserPackages(TblUserAuth userAuth, ArkContext db)
        {
            var _qUi = from a in db.TblUserAuth
                       join b in db.TblUserBusinessPackage on a.Id equals b.UserAuthId
                       join c in db.TblUserDepositRequest on b.UserDepositRequestId equals c.Id
                       join d in db.TblBusinessPackage on b.BusinessPackageId equals d.Id
                       join e in db.TblBusinessPackageType on d.PackageTypeId equals e.Id
                       join f in db.TblCurrency on c.SourceCurrencyId equals f.Id
                       //join g in db.TblWalletType on c.TargetWalletTypeId equals g.Id
                       orderby b.CreatedOn descending
                       where a.Id == userAuth.Id
                       select new TblUserBusinessPackage
                       {
                         Id = b.Id,
                         ActivationDate = b.ActivationDate,
                         UserDepositRequest = new TblUserDepositRequest { Address = c.Address, Amount = c.Amount, CreatedOn = c.CreatedOn, DepositStatus = c.DepositStatus, ExpiryDate = c.ExpiryDate, Id = c.Id, IsEnabled = c.IsEnabled, Remarks = c.Remarks, SourceCurrency = f},
                         IsEnabled = b.IsEnabled,
                         CreatedOn = b.CreatedOn,
                         PackageStatus = b.PackageStatus,
                         CancellationDate = b.CancellationDate,
                         BusinessPackage =  new TblBusinessPackage { PackageType = e, PackageName = d.PackageName, PackageCode = d.PackageCode, Id = d.Id, CreatedOn = d.CreatedOn, IsEnabled = d.IsEnabled, ValueFrom = d.ValueFrom, ValueTo = d.ValueTo, PackageDescription = d.PackageDescription}
                       };

            List<TblUserBusinessPackage> _ubp = _qUi.ToList<TblUserBusinessPackage>();

            return _ubp;
        }
        public List<TblBusinessPackage> GetAll(TblUserMap tblUserMap, ArkContext db)
        {
            var _qUi = from a in db.TblBusinessPackage
                       join b in db.TblCurrency on a.CurrencyId equals b.Id
                       where a.IsEnabled == true

                       orderby a.CreatedOn descending
                       select new TblBusinessPackage
                       {
                           Id = a.Id,
                           CalculationMethod = a.CalculationMethod,
                           CreatedOn = a.CreatedOn,
                           DiscountType = a.DiscountType,
                           DiscountValue = a.DiscountValue,
                           IsEnabled = a.IsEnabled,
                           NetworkValue = a.NetworkValue,
                           PackageCode = a.PackageCode,
                           PackageName = a.PackageName,
                           ValueFrom = tblUserMap.SponsorUserId == 2 ?  a.ValueFrom : (a.ValueFrom - a.DiscountValue),
                           ValueTo = tblUserMap.SponsorUserId == 2 ? a.ValueTo : (a.ValueTo - a.DiscountValue),
                           PackageDescription = a.PackageDescription,
                           Currency = b
                       };

            List<TblBusinessPackage> _ubp = _qUi.ToList<TblBusinessPackage>();

            return _ubp;
        }
    }
}
