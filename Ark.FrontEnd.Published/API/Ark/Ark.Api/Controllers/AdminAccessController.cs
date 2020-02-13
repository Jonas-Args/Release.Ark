using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Ark.AppService;
using Ark.Entities.BO;
using Ark.Entities.DTO;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Ark.Entities.Enums;

namespace Ark.Api.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AdminAccessController : ControllerBase
    {
        [HttpGet("UserList")]
        public ActionResult UserList()
        {
            AdminResponseBO _apiResponse = new AdminResponseBO();
            try
            {
                AdminAccessAppService adminAccessAppService = new AdminAccessAppService();
                _apiResponse.UserList = adminAccessAppService.GetAllUsers();
                _apiResponse.UserDepositRequests = adminAccessAppService.GetAllDepositRequest();

                _apiResponse.HttpStatusCode = "200";
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
    }
}
