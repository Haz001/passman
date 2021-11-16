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

function generateOneTimePassword($conn, $userInfo)
{
	$to = $userInfo["email"];
	$subject = "OTP from PassMan";
	//    $txt = uniqid("otp_", true);
	$txt = "otp_" . bin2hex(openssl_random_pseudo_bytes(4));
	$headers = "From: webmaster@harrysy.red";
	mail($to, $subject, "Your OTP passcode is:\r\n" + $txt, $headers); //sets up email parameters and mails it to the user
	mysqli_query($conn, 'DELETE FROM otp WHERE user_id = "' . $userInfo["user_id"] . '"');
	$sql = "INSERT INTO otp (user_id, otp, otp_created) VALUES (?,?,?)"; //inserts the otp into the otp database linked to the user
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "sss", $userInfo["user_id"], $txt, time());
	mysqli_stmt_execute($stmt);
	$_SESSION["tempID"] = $userInfo["user_id"];
	header("location:../otp.php");
	exit();
}

function loginUser($conn, $pD)
{
	$userInfo = isUnique($conn, $pD); //uses the isUnique function to check if user exists and to get user details
	// from database
	if ($userInfo == false) {
		header("location:../login.php?error=notfound");
		exit();
	}
	if (password_verify($pD["password"], $userInfo["master_password"])) {
		generateOneTimePassword($conn, $userInfo);
		//checks if the password hash inputted and the password
		//hash on the database match, the one time passcode function is then called
	} else {
		header("location:../login.php?error=notfound");
		exit();
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
		$ciphertext = openssl_encrypt($data, $cipher, $key, $options = 0, $iv); //encrypts
		return $ciphertext;
	} else {
		return -1;
	}
}

function decryptData($ciphertext, $key, $iv)
{
	$cipher = "aes-256-cbc"; //define cipher to use
	if (in_array($cipher, openssl_get_cipher_methods())) { //checks if cipher is valid
		$plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv); //decrypts
		return $plaintext;
	} else {
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
