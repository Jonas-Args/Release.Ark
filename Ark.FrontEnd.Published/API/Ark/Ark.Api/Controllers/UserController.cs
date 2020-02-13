using System;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Ark.API.Controllers;
using Ark.AppService;
using Ark.Entities.BO;
using Ark.Entities.DTO;
using Ark.Entities.Enums;
using Ark.ExternalUtilities.Models;
using Ark.ExternalUtilities;

namespace Ark.Api.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UserController : ControllerBase
    {
        [HttpPost("Create")]
        public async Task<ActionResult> CreateAsync([FromBody] UserBO userBO)
        {
            UserAppService userAppService = new UserAppService();
            ApiResponseBO _apiResponse = new ApiResponseBO();
            MailAppService mailAppService = new MailAppService();

            try
            {
                bool _r = await userAppService.CreateAsync(userBO).ConfigureAwait(true);
                bool response = mailAppService.SendSmtp(userBO, EmailType.EmailConfirmation);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "User successfully created, Email sent: " + response;
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.InnerException != null ? ex.InnerException.Message : ex.Message;
                _apiResponse.Status = "Error";
            }

            return Ok(_apiResponse);
        }

        [HttpPost("Authenticate")]
        public ActionResult Authenticate([FromBody] UserBO userBO)
        {
            UserAppService userAppService = new UserAppService();
            UserResponseBO _apiResponse = new UserResponseBO();
            if (ModelState.IsValid)
            {
                try
                {
                    UserResponseBO userAuthResponse = userAppService.Authenticate(userBO);

                    _apiResponse.UserInfo = userAuthResponse.UserInfo;
                    _apiResponse.UserWallet = userAuthResponse.UserWallet;
                    _apiResponse.UserRole = userAuthResponse.UserRole;

                    // SET SESSIONS
                    SessionController sessionController = new SessionController();
                    sessionController.CreateSession(userAuthResponse, HttpContext.Session);


                    _apiResponse.HttpStatusCode = "200";
                    _apiResponse.Message = "User successfully authenticated";
                    _apiResponse.Status = "Success";

                    return Ok(_apiResponse);

                }
                catch (Exception ex)
                {
                    _apiResponse.HttpStatusCode = "500";
                    _apiResponse.Message = ex.Message;
                    _apiResponse.Status = "Error";

                    return Ok(_apiResponse);
                }
            }
            else
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = "Please input the required credentials";
                _apiResponse.Status = "Error";

                return Ok(_apiResponse);
            }


        }

        [HttpPut("Update")]
        public ActionResult Update([FromBody] UserBO userBO)
        {
            UserAppService userAppService = new UserAppService();
            ApiResponseBO _apiResponse = new ApiResponseBO();

            try
            {
                userAppService.CreateAsync(userBO);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "User successfully created";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.InnerException.Message;
                _apiResponse.Status = "Error";
            }

            return Ok(_apiResponse);
        }

        [HttpDelete("Delete")]
        public ActionResult Delete()
        {
            UserWalletAppService userWalletAppService = new UserWalletAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserWallet = userWalletAppService.GetAllBO(userAuth);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("Profile")]
        public ActionResult Profile()
        {
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                UserAppService userAppService = new UserAppService();
                TblUserInfo userInfo = userAppService.Get(userAuth);

                TblUserRole userRole= new TblUserRole();
                userRole = userAppService.GetUserRole(userAuth);

                _apiResponse.UserInfo = userInfo;
                _apiResponse.UserAuth = userAuth;
                _apiResponse.UserRole = userRole;

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "UserProfile GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("Wallet")]
        public ActionResult Wallet()
        {
            UserWalletAppService userWalletAppService = new UserWalletAppService();
            UserWalletAddressAppService userWalletAddressAppService = new UserWalletAddressAppService();
            UserBusinessPackageAppService userBusinessPackageAppService = new UserBusinessPackageAppService();

            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserWallet = userWalletAppService.GetAllBO(userAuth);
                _apiResponse.UserWalletAddress = userWalletAddressAppService.GetAll(userAuth);
                _apiResponse.UserDepositRequests = userBusinessPackageAppService.GetUserDepositRequests(userAuth, DepositStatus.PendingPayment);
                
                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("Wallet/Transactions")]
        public ActionResult WalletTransactions()
        {
            UserWalletAppService userWalletAppService = new UserWalletAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserWalletTransactions = userWalletAppService.GetAllTransactions(userAuth);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("Affiliate/TradeTransactions")]
        public ActionResult AffiliateTradeTransactions()
        {
            UserAffiliateProfitsResponseBO _apiResponse = new UserAffiliateProfitsResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);


                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }
        [HttpGet("BusinessPackages")]
        public ActionResult BusinessPackages()
        {
            UserBusinessPackageAppService userBusinessPackageAppService = new UserBusinessPackageAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.BusinessPackages = userBusinessPackageAppService.GetAll(userAuth);

                _apiResponse.HttpStatusCode = "200";
                //_apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
        }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("BinaryMap")]
        public ActionResult BinaryMap()
        {
            UserMapAppService userMapAppService = new UserMapAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserBinaryMap = userMapAppService.GetBinary(userAuth);

                _apiResponse.HttpStatusCode = "200";
                //_apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("UnilevelMap")]
        public ActionResult UnilevelMap()
        {
            UserMapAppService userMapAppService = new UserMapAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserUnilevelMap = userMapAppService.GetUnilevel(userAuth);

                _apiResponse.HttpStatusCode = "200";
                //_apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

        [HttpGet("UserIncomeTransactions")]
        public ActionResult UserIncomeTransactions()
        {
            UserIncomeAppService userIncomeAppService = new UserIncomeAppService();
            UserResponseBO _apiResponse = new UserResponseBO();

            try
            {
                // GET SESSIONS
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.UserIncomeTransactions = userIncomeAppService.GetUserIncomeTransactions(userAuth);

                _apiResponse.HttpStatusCode = "200";
                //_apiResponse.Message = "UserWallet GET";
                _apiResponse.Status = "Success";
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.Message;
                _apiResponse.Status = "Error";

            }

            return Ok(_apiResponse);
        }

    }
}
