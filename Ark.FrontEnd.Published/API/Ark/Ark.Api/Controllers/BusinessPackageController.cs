using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Ark.API.Controllers;
using Ark.AppService;
using Ark.Entities.BO;
using Ark.Entities.DTO;
using Ark.Entities.Enums;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;

namespace Ark.Api.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class BusinessPackageController : ControllerBase
    {
        [HttpPost("Buy")]
        public ActionResult Create([FromBody] UserBusinessPackageBO userBusinessPackageBO)
        {
            UserBusinessPackageAppService userBusinessPackageAppService = new UserBusinessPackageAppService();
            ApiResponseBO _apiResponse = new ApiResponseBO();

            try
            {
                userBusinessPackageAppService.Create(userBusinessPackageBO);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "Package successfully purchased";
                _apiResponse.RedirectUrl = "/dashboard";
                _apiResponse.Status = "Success";

                return Ok(_apiResponse);
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.InnerException != null ? ex.InnerException.Message : ex.Message;
                _apiResponse.Status = "Error";
                return BadRequest(_apiResponse);
            }

        }
        
        [HttpGet]
        public ActionResult Get()
        {
            UserBusinessPackageAppService userBusinessPackageAppService = new UserBusinessPackageAppService();
            BusinessPackagesResponseBO _apiResponse = new BusinessPackagesResponseBO();

            try
            {
                SessionController sessionController = new SessionController();
                TblUserAuth userAuth = sessionController.GetSession(HttpContext.Session);

                _apiResponse.BusinessPackages = userBusinessPackageAppService.GetAllBusinessPackages(userAuth);
                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "Package successfully purchased";
                _apiResponse.Status = "Success";

                return Ok(_apiResponse);
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.InnerException != null ? ex.InnerException.Message : ex.Message;
                _apiResponse.Status = "Error";
                return Ok(_apiResponse);
            }
        }

        [HttpPost("Update")]
        public ActionResult Update([FromBody] UserBusinessPackageBO userBusinessPackageBO)
        {
            UserBusinessPackageAppService userBusinessPackageAppService = new UserBusinessPackageAppService();
            MailAppService mailAppService = new MailAppService();
            ApiResponseBO _apiResponse = new ApiResponseBO();

            try
            {
                TblUserAuth userAuth = userBusinessPackageAppService.Update(userBusinessPackageBO);
                bool response = mailAppService.SendSmtp(new UserBO { Email = userAuth.UserName } ,EmailType.PackagePurchaseConfirmation);

                _apiResponse.HttpStatusCode = "200";
                _apiResponse.Message = "Package successfully purchased";
                _apiResponse.RedirectUrl = "/admin/customers";
                _apiResponse.Status = "Success";

                return Ok(_apiResponse);
            }
            catch (Exception ex)
            {
                _apiResponse.HttpStatusCode = "500";
                _apiResponse.Message = ex.InnerException != null ? ex.InnerException.Message : ex.Message;
                _apiResponse.Status = "Error";
                return BadRequest(_apiResponse);
            }

        }
    }
}
