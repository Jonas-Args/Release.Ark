<?php
$_mid = "<MID ID>"; //<-- your merchant id
$_requestid = substr(uniqid(), 0, 13);
$_ipaddress = "192.168.10.1";
$_noturl = ""; // url where response is posted
$_resurl = ""; //url of merchant landing page
$_cancelurl = ""; //url of merchant landing page
$_fname = ""; // kindly set this to first name of the cutomer
$_mname = ""; // kindly set this to middle name of the cutomer
$_lname = ""; // kindly set this to last name of the cutomer
$_addr1 = ""; // kindly set this to address1 of the cutomer
$_addr2 = "";// kindly set this to address2 of the cutomer
$_city = ""; // kindly set this to city of the cutomer
$_state = ""; // kindly set this to state of the cutomer
$_country = ""; // kindly set this to country of the cutomer
$_zip = ""; // kindly set this to zip/postal of the cutomer
$_sec3d = "try3d"; //
$_email = ""; // kindly set this to email of the cutomer
$_phone = ""; // kindly set this to phone number of the cutomer
$_mobile = ""; // kindly set this to mobile number of the cutomer
$_clientip = $_SERVER['REMOTE_ADDR'];
$_amount = ""; // kindly set this to the total amount of the transaction. Set the amount to 2 decimal point before generating signature.
$_currency = ""; //PHP or USD
$forSign = $_mid . $_requestid . $_ipaddress . $_noturl . $_resurl .  $_fname . $_lname . $_mname . $_addr1 . $_addr2 . $_city . $_state . $_country . $_zip . $_email . $_phone . $_clientip . $_amount . $_currency . $_sec3d;
$cert = "<MID KEY>"; //<-- your merchant key

echo $_mid . "<hr />";
echo $cert . "<hr />";
echo $forSign . "<hr />";

$_sign = hash("sha512", $forSign.$cert);
$xmlstr = "";

$strxml = "";

$strxml = $strxml . "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
$strxml = $strxml . "<Request>";
$strxml = $strxml . "<orders>";
$strxml = $strxml . "<items>";
$strxml = $strxml . "<Items>";
$strxml = $strxml . "<itemname>item 1</itemname><quantity>1</quantity><amount>10.00</amount>"; // pls change this value to the preferred item to be seen by customer. (eg. Room Detail (itemname - Beach Villa, 1 Room, 2 Adults       quantity - 0       amount - 10)) NOTE : total amount of item/s should be equal to the amount passed in amount xml node below.
$strxml = $strxml . "</Items>";
$strxml = $strxml . "</items>";
$strxml = $strxml . "</orders>";
$strxml = $strxml . "<mid>" . $_mid . "</mid>";
$strxml = $strxml . "<request_id>" . $_requestid . "</request_id>";
$strxml = $strxml . "<ip_address>" . $_ipaddress . "</ip_address>";
$strxml = $strxml . "<notification_url>" . $_noturl . "</notification_url>";
$strxml = $strxml . "<response_url>" . $_resurl . "</response_url>";
$strxml = $strxml . "<cancel_url>" . $_cancelurl . "</cancel_url>";
$strxml = $strxml . "<mtac_url></mtac_url>"; // pls set this to the url where your terms and conditions are hosted
$strxml = $strxml . "<descriptor_note>''</descriptor_note>"; // pls set this to the descriptor of the merchant ""
$strxml = $strxml . "<fname>" . $_fname . "</fname>";
$strxml = $strxml . "<lname>" . $_lname . "</lname>";
$strxml = $strxml . "<mname>" . $_mname . "</mname>";
$strxml = $strxml . "<address1>" . $_addr1 . "</address1>";
$strxml = $strxml . "<address2>" . $_addr2 . "</address2>";
$strxml = $strxml . "<city>" . $_city . "</city>";
$strxml = $strxml . "<state>" . $_state . "</state>";
$strxml = $strxml . "<country>" . $_country . "</country>";
$strxml = $strxml . "<zip>" . $_zip . "</zip>";
$strxml = $strxml . "<secure3d>" . $_sec3d . "</secure3d>";
$strxml = $strxml . "<trxtype>sale</trxtype>";
$strxml = $strxml . "<email>" . $_email . "</email>";
$strxml = $strxml . "<phone>" . $_phone . "</phone>";
$strxml = $strxml . "<mobile>" . $_mobile . "</mobile>";
$strxml = $strxml . "<client_ip>" . $_clientip . "</client_ip>";
$strxml = $strxml . "<amount>" . $_amount . "</amount>";
$strxml = $strxml . "<currency>" . $_currency . "</currency>";
$strxml = $strxml . "<mlogo_url></mlogo_url>";// pls set this to the url where your logo is hosted
$strxml = $strxml . "<pmethod></pmethod>";
$strxml = $strxml . "<signature>" . $_sign . "</signature>";
$strxml = $strxml . "</Request>";
$b64string =  base64_encode($strxml);
echo "<pre>" . $strxml . "</pre><hr />";
echo $b64string . "<hr />";

echo '<form name="form1" method="post" action="https://testpti.payserv.net/webpaymentv2/default.aspx">
   								<input type="text" name="paymentrequest" id="paymentrequest" value="'.$b64string.'" style="width:800px; padding: 10px;">
							    <input type="submit">
						</form>';

?>
