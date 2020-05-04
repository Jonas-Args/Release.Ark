<?php


$url = 'http://localhost:55006/api/user/VerifyEmail';
$data = array(
	'UserName' => $_GET['UserName']
);

// use key 'http' even if you send the request to https://...
$options = array(
	'http' => array(
		'header' => "Content-type: application/json",
		'method' => 'POST',
		'content' => json_encode($data)
	)
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$_r = json_decode($result);

if ($_r->httpStatusCode != "500")
{
//header("Location: /users/login");
//echo "Successfully Validated!";
}
else {
	echo "Validation Error!";
}


?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>Ark PH | Email Verified</title></head>
    <body>
		<img style="margin-left:auto; margin-right:auto; display:block; max-width:90%" src="/public/img/email_materials/EMAIL_SUCCESSFULLY_ACTIVATED.jpg" alt="Alternate Text" />
	</body>

<script>
	function _redirect() {
		window.location.replace('/users/login');
	}
	window.setTimeout(_redirect, 5000);	
</script>

</html>
