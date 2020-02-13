using Microsoft.VisualStudio.TestTools.UnitTesting;
using Ark.Entities.BO;
using Ark.Entities.Enums;
using Ark.AppService;

namespace Ark.UnitTest
{
    [TestClass]
    public class UnitTest1
    {
        [TestMethod]
        public void EmailTest()
        {
            UserBO userBO = new UserBO() {
                FirstName = "Jay",
                LastName = "Eraldo",
                Email = "jjeeraldo@gmail.com"            
            };
            MailAppService mailAppService = new MailAppService();
            bool response = mailAppService.SendSmtp(userBO, EmailType.EmailConfirmation);
        }
    }
}
