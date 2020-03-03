<?php


$url = 'http://localhost:55006/api/user/VerifyEmail';
		$data = array(
			'UserName' => $_GET['UserName']
			);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => json_encode($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		if ($_r->httpStatusCode != "500")
		{
	    header("Location:" . $_SERVER['SERVER_NAME'] . "/");
		//echo "Successfully Validated!";
        }
        else{
            echo "Validation Error!";
        }


?>