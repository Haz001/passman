<?php

function emptyFields($toSearch): bool
{
	$rt = true;
	foreach ($toSearch as $value) { //Searches each value in $toSearch and returns false if any empty values are found
		if (empty($value)) {
			$rt = false;
		}
	}
	return $rt;
}

function isUnique($conn, $pD)
{
	$sql = "SELECT * FROM user WHERE username = ? OR  email = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) { //checks if statement prepares correctly
		header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $pD["username"], $pD["email"]);
	mysqli_stmt_execute($stmt); //executes sql query
	$stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
	if ($row = mysqli_fetch_assoc($stmtresult)) {  // creates an associative array of the sql result
		return $row;
	} else {
		$stmtresult = false;
		return $stmtresult;
	}
	mysqli_stmt_close($stmt);
}

function isUniqueWeb($conn, $pD)
{
	$sql = "SELECT * FROM saved_website WHERE website_name = ? OR  web_address = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) { //checks if statement prepares correctly
		header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $pD["website_name"], $pD["web_address"]);
	mysqli_stmt_execute($stmt); //executes sql query
	$stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
	if ($row = mysqli_fetch_assoc($stmtresult)) {  // creates an associative array of the sql result
		return $row;
	} else {
		$stmtresult = false;
		return $stmtresult;
	}
	mysqli_stmt_close($stmt);
}
function grabIp()
{
	//whether ip is from the share internet  
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	//whether ip is from the proxy  
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	//whether ip is from the remote address  
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
function generateOneTimePassword($conn, $userInfo, $pD)
{
	$to = $userInfo["email"];
	$subject = "OTP from PassMan";
	// $txt = uniqid("otp_", true);
	$txt = "otp_" . bin2hex(openssl_random_pseudo_bytes(4));
	$tempPath = "./temp/email.html";
	try //tries to read email template
	{
		$f = fopen($tempPath, 'r');
		$temp = fread($f, filesize($tempPath));
		fclose($f);
	} catch (Exception $ex) {
		$temp = '$name here is your code:<br/>$code';
	}
	if (($temp == "") or ($temp == null)) {
		$temp = '$name here is your code:<br/>$code';
	}
	try {
		include "getBrowserInfo.php";
		$browser = getOS() . " - " . getBrowser();
	} catch (Exception $ex) {
		$browser = grabIp();
	}
	$body = str_replace('$device', $browser, str_replace('$code', $txt, str_replace('$name', $userInfo["first_name"], $temp)));
	$headers = "MIME-Version: 1.0" . "\r\n"; // tells email provider to accept next line
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; // tells email provider that this email is formatted in HTML
	$headers .= "From: otp@passman.harrysy.red"; //tells email that it was sent by
	//mail($to, $subject, "Your OTP passcode is:\r\n" . $txt, $headers); //sets up email parameters and mails it to the user
	mail($to, $subject, $body, $headers); //sets up email parameters and mails it to the user
	mysqli_query($conn, 'DELETE FROM otp WHERE user_id = "' . $userInfo["user_id"] . '"');
	$sql = "INSERT INTO otp (user_id, otp, otp_created) VALUES (?,?,?)"; //inserts the otp into the otp database linked to the user
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "sss", $userInfo["user_id"], $txt, time());
	mysqli_stmt_execute($stmt);
	$_SESSION["tempID"] = $userInfo["user_id"];
	if (!isset($pD["location"])) {
		header("location:../otp.php");
		exit();
	} else {
		response("otp");
	}
}
function loginUser($conn, $pD)
{
	$userInfo = isUnique($conn, $pD); //uses the isUnique function to check if user exists and to get user details
	// from database
	if ($userInfo == false) {
		if (!isset($pD["location"])) {
			header("location:../login.php?error=notfound");
			exit();
		} else {
			response("error", "notfound");
		}
	}
	if (password_verify($pD["password"], $userInfo["master_password"])) {
		setcookie("key", hash("sha3-512", $pD["password"]), 0, "/", "passman.harrysy.red", true);

		generateOneTimePassword($conn, $userInfo, $pD);
		//checks if the password hash inputted and the password
		//hash on the database match, the one time passcode function is then called
	} else {
		if (!isset($pD["location"])) {
			header("location:../login.php?error=notfound");
			exit();
		} else {
			response("error", "notfound");
		}
	}
}
function signUp($conn, $pD)
{
	$sql = "INSERT INTO user (first_name, last_name, username, email, master_password, dob, mobile) VALUES (?,?,?,?,?,?,?);"; //starts to
	// prepare the sql statement
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	$pswdHash = password_hash($pD["password"], PASSWORD_DEFAULT); //hashes the users password before it is stored

	mysqli_stmt_bind_param($stmt, "sssssss", $pD["first_name"], $pD["last_name"], $pD["username"], $pD["email"], $pswdHash, $pD["dob"], $pD["mobile"]);
	//bind parameters to statement
	if (!mysqli_stmt_execute($stmt)) { //executes the INSERT statement
		header("location:../signup.php?error=exfailed");
		exit();
	}
	header("location:../signup.php?error=success");
	exit();
}
function generateIV()
{
	$cipher = "aes-256-cbc"; //define cipher to use
	$ivlen = openssl_cipher_iv_length($cipher); //defines iv's length
	$iv = openssl_random_pseudo_bytes($ivlen); //creates iv using random bytes

	return $iv;
}

function encryptData($data, $key, $iv)
{
	$cipher = "aes-256-cbc"; //define cipher to use
	if (in_array($cipher, openssl_get_cipher_methods())) { //checks if cipher is valid
		$ciphertext = openssl_encrypt($data, $cipher, $key, 0, $iv); //encrypts
		return $ciphertext;
	} else {
		echo "can't securely encrypt and decrypt password";
		return -1;
	}
}

function decryptData($ciphertext, $key, $iv)
{
	$cipher = "aes-256-cbc"; //define cipher to use
	if (in_array($cipher, openssl_get_cipher_methods())) { //checks if cipher is valid
		$plaintext = openssl_decrypt($ciphertext, $cipher, $key, 0, $iv); //decrypts
		return $plaintext;
	} else {
		echo "can't securely encrypt and decrypt password";
		return -1;
	}
}

function createWebEntry($conn, $pD)
{
	$sql = "INSERT INTO saved_website (user_id, website_name, web_address) VALUES (?,?,?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../index.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "sss", $_SESSION["user_id"], $pD["website_name"], $pD["web_address"]);
	if (!mysqli_stmt_execute($stmt)) { //executes the INSERT statement
		header("location:../index.php?error=stmtfailed");
		exit();
	}
	header("location:../index.php?error=success");
}

function passwordComplex($pswd)
{
	if (strlen($pswd) < 20) {
		$uppercase = preg_match('@[A-Z]@', $pswd);
		$lowercase = preg_match('@[a-z]@', $pswd);
		$number    = preg_match('@[0-9]@', $pswd);
		$specialChars = preg_match('@[^\w]@', $pswd);
		if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pswd) < 8) {
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}
/**
 * $conn - database connection
 * $user_identifier (array)
 * 		[0] - type (0 - user_id,)
 * 		[1] - 
 */
function getWebsiteList($conn, $user_identifier)
{
	$user_id = "";
	if($user_identifier[0] == 0){
		$user_id = $user_identifier[1];
	}else{
		$user_id = getUidWhereAuthCode($user_identifier[1]);
	}
	$sql = "SELECT website_id, website_name, web_address from user JOIN saved_website ON user.user_id = saved_website.user_id WHERE user.user_id = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $user_id);
	mysqli_stmt_execute($stmt);
	$stmtresult =  mysqli_stmt_get_result($stmt);
	$result = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
	mysqli_free_result($stmtresult);
	return json_encode($result);
}
function getPasswordList($conn, $user_id, $website_id, $key)
{
	//$sql = "SELECT website_password.website_id, password_id, username, password, vi from website_password JOIN [SELECT website_id, from user JOIN saved_website ON user.user_id = saved_website.user_id WHERE user.user_id = ?] where website";
	$sql = "SELECT website_password.* from website_password JOIN (SELECT website_id FROM user JOIN saved_website ON user.user_id = saved_website.user_id where user.user_id = ?) as websites on website_password.website_id = websites.website_id where website_password.website_id = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $user_id, $website_id);
	mysqli_stmt_execute($stmt);
	$stmtresult =  mysqli_stmt_get_result($stmt);
	$cipher = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
	mysqli_free_result($stmtresult);
	$result = [];
	for ($i = 0; $i < sizeof($cipher); $i++) {
		$result[$i] = [];
		$result[$i]["website_id"]  = $cipher[$i]["website_id"];
		$result[$i]["password_id"] = $cipher[$i]["password_id"];
		$result[$i]["username"] = decryptData($cipher[$i]["username"], $key, base64_decode($cipher[$i]["iv"]));
		$result[$i]["password"] = decryptData($cipher[$i]["password"], $key, base64_decode($cipher[$i]["iv"]));
	}
	return json_encode($result);
}

function response($response, $error = "none")
{
	$return = array("response" => $response, "error" => $error);
	echo json_encode($return);
	exit();
}
function getUidWhereAuthCode($conn, $authToken){
	/**TODO:
	 * - make invalid in 2 weeks after date_created
	 * - add function to make invalid and check if invalid here
	 */
	$sql = "SELECT user_id from auth_token where auth_token = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt,$sql);
	mysqli_stmt_bind_param($stmt,'s',);
	mysqli_stmt_
	
}