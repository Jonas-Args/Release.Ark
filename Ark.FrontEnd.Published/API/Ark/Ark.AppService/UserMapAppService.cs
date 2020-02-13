using System;
using Ark.Entities.DTO;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.DataAccessLayer;
using System.Collections.Generic;


namespace Ark.AppService
{
    public class UserMapAppService
    {
        public bool Create(UserBO userBO, TblUserAuth userAuth, DataAccessLayer.ArkContext db)
        {
            UserMapRepository userMapRepository = new UserMapRepository();
            TblUserMap _userMap = new TblUserMap();

            //userBO = SetDefaultUpline(userBO, db, true);
            BinaryMapStatus _y = Validate(userBO, db);

            if (_y == BinaryMapStatus.BinaryPositionTaken)
            {
                userBO = SetDefaultUpline(userBO, db, false);
                UserBO _user = GetExtremeBinary(userBO, db);
                userBO.BinarySponsorID = _user.Id.ToString();
                userBO.BinaryPosition = _user.BinaryPosition;
            }
            else if (_y == BinaryMapStatus.Available)
            {
                userBO = SetDefaultUpline(userBO, db, false);
            }
            else if (_y == BinaryMapStatus.InvalidBinarySponsor)
            {
                throw new ArgumentException("Binary Sponsor ID is invalid");
            }
            else if (_y == BinaryMapStatus.InvalidDirectSponsor)
            {
                throw new ArgumentException("Source Code is invalid");
            }

            //_userMap.UplineUserId = long.Parse(userBO.BinarySponsorID);
            _userMap.SponsorUserId = long.Parse(userBO.DirectSponsorID);

            TblUserMap userMap = new TblUserMap
            {
                Id = userAuth.Id,
                SponsorUserId = _userMap.SponsorUserId,
                //UplineUserId = _userMap.UplineUserId,
                Position = short.Parse(userBO.BinaryPosition),
                UserUid = userBO.Uid
            };

            return userMapRepository.Create(userMap, db);
        }
        public UserMapBO GetBinary(TblUserAuth userAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                using var transaction = db.Database.BeginTransaction();
                UserMapRepository userMapRepository = new UserMapRepository();
                UserMapBO userMap = userMapRepository.GetMap(userAuth);

                return userMap;
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    using var transaction = db.Database.BeginTransaction();
                    UserMapRepository userMapRepository = new UserMapRepository();
                    UserMapBO userMap = userMapRepository.GetMap(userAuth);

                    return userMap;
                }
            }
        }
        public UnilevelMapBO GetUnilevel(TblUserAuth userAuth, DataAccessLayer.ArkContext db = null)
        {
            if (db != null)
            {
                UserMapRepository userMapRepository = new UserMapRepository();
                return userMapRepository.GetUnilevel(userAuth, db);
            }
            else
            {
                using (db = new DataAccessLayer.ArkContext())
                {
                    UserMapRepository userMapRepository = new UserMapRepository();
                    return userMapRepository.GetUnilevel(userAuth, db);
                }
            }
        }
        public UserBO GetExtremeBinary(UserBO userBO, DataAccessLayer.ArkContext db)
        {
            UserAuthRepository userAuthRepository = new UserAuthRepository();
            UserMapRepository userMapRepository = new UserMapRepository();

            if (userBO.BinarySponsorID != null)
            {
                TblUserAuth binarySponsorUser = userAuthRepository.GetByID(long.Parse(userBO.BinarySponsorID), db);

                List<TblUserMap> _userMapList = userMapRepository.GetAll(new TblUserMap { UplineUserId = binarySponsorUser.Id, UserUid = "" }, db).FindAll(i => i.Position == short.Parse(userBO.BinaryPosition));

                if (_userMapList.Count > 0)
                {
                    //return false;
                    //throw new ArgumentException("Binary position is already taken");
                    UserBO _x = GetExtremeBinary(new UserBO { BinarySponsorID = _userMapList[0].Id.ToString(), BinaryPosition = "1" }, db);
                    //UserBO _x = GetExtremeBinary(new UserBO { BinarySponsorID = _userMapList[0].Id.ToString(), BinaryPosition = userBO.BinaryPosition }, db);
                    return _x;
                }
                else
                {
                    //if (userBO.BinaryPosition == "2")
                    //{
                    //    _userMapList = userMapRepository.GetAll(new TblUserMap { UplineUserId = binarySponsorUser.Id, UserUid = "" }, db).FindAll(i => i.Position == 1);
                    //    if (_userMapList.Count > 0)
                    //    {
                    //        return new UserBO { Id = binarySponsorUser.Id, BinaryPosition = userBO.BinaryPosition };
                    //    }
                    //    else
                    //    {
                    //        return new UserBO { Id = binarySponsorUser.Id, BinaryPosition = "1" };
                    //    }
                    //}
                    //else
                    //{
                    //    return new UserBO { Id = binarySponsorUser.Id, BinaryPosition = userBO.BinaryPosition};
                    //}
                    return new UserBO { Id = binarySponsorUser.Id, BinaryPosition = userBO.BinaryPosition };
                }
            }
            else
            {
                throw new ArgumentException("Binary Sponsor is null");
            }


        }
        public UserBO SetDefaultUpline(UserBO userBO, DataAccessLayer.ArkContext db, bool isEnabled = true)
        {
            UserAuthRepository userAuthRepository = new UserAuthRepository();
            TblUserAuth directSponsorUser = userAuthRepository.GetByUID(userBO.DirectSponsorID, db);
            TblUserAuth binarySponsorUser = userAuthRepository.GetByUID(userBO.BinarySponsorID, db);

            if (isEnabled == true)
            {
                if (directSponsorUser == null)
                {
                    userBO.DirectSponsorID = "1";
                }
                if (binarySponsorUser == null)
                {
                    userBO.BinarySponsorID = "1";
                }
            }
            else
            {
                userBO.DirectSponsorID = directSponsorUser != null ? directSponsorUser.Id.ToString() : "2";
                userBO.BinarySponsorID = binarySponsorUser != null ? binarySponsorUser.Id.ToString() : "2";
            }

            return userBO;
        }
        public BinaryMapStatus Validate(UserBO userBO, DataAccessLayer.ArkContext db)
        {
            UserAuthRepository userAuthRepository = new UserAuthRepository();
            UserMapRepository userMapRepository = new UserMapRepository();

            if (userBO.BinarySponsorID != null)
            {
                TblUserAuth binarySponsorUser = userAuthRepository.GetByUID(userBO.BinarySponsorID, db);

                if (binarySponsorUser == null)
                {
                    return BinaryMapStatus.InvalidBinarySponsor;
                    //throw new ArgumentException("Binary Sponsor ID is invalid");
                }
                else
                {
                    List<TblUserMap> _userMapList = userMapRepository.GetAll(new TblUserMap { UplineUserId = binarySponsorUser.Id, UserUid = ""}, db).FindAll(i => i.Position == short.Parse(userBO.BinaryPosition));
                    if (_userMapList.Count > 0)
                    {
                        return BinaryMapStatus.BinaryPositionTaken;
                        //throw new ArgumentException("Binary position is already taken");
                    }
                }
            }

            if (userBO.DirectSponsorID != null)
            {
                TblUserAuth directSponsorUser = userAuthRepository.GetByUID(userBO.DirectSponsorID, db);

                if (directSponsorUser == null)
                {
                    return BinaryMapStatus.InvalidDirectSponsor;
                    //throw new ArgumentException("Introducer ID is invalid");
                }
            }



            return BinaryMapStatus.Available;

        }
    }
}

