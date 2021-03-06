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
	$txt = "otp_" . bin2hex(openssl_random_pseudo_bytes(4));// makes a 8 letter otp
	$tempPath = "./temp/email.html";
	try //tries to read email template
	{
		$f = fopen($tempPath, 'r');// tries to open template
		$temp = fread($f, filesize($tempPath));//reads template
		fclose($f);//closes it
	} catch (Exception $ex) {
		$temp = '$name here is your code:<br/>$code';//failsafe if didn't work
	}
	if (($temp == "") or ($temp == null)) {
		$temp = '$name here is your code:<br/>$code';//another failsave
	}
	try {
		include "getBrowserInfo.php";// imports function to find browser info
		$browser = getOS() . " - " . getBrowser();//gets browser info
	} catch (Exception $ex) {
		$browser = grabIp();//failsafe to just use users ip
	}
	$body = str_replace('$device', $browser, str_replace('$code', $txt, str_replace('$name', $userInfo["first_name"], $temp)));//replaces values in template with data
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
		$censoredEmail = explode('@', $userInfo["email"]);
		if (sizeof($censoredEmail) > 1)
			header("location:../otp.php?email=@" . $censoredEmail[1]);
		else
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
		if ($userInfo["masterkey"] == "") {
			//setcookie("key", hash("sha3-512", $pD["password"]), 0, "/", "passman.harrysy.red", true);
			setcookie("key", keyGen($conn, $pD["password"], $userInfo["user_id"]), 0, "/", "passman.harrysy.red", true);
		} else {
			setcookie("key", keyGet($conn, $pD["password"], $userInfo["user_id"]), 0, "/", "passman.harrysy.red", true);
		}

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
function keyGet($conn, $password, $user_id)
{
	$sql = "select masterkey, masteriv from user where user_id = ?;"; //sql statement to get masterkey and masteriv
	$stmt = mysqli_stmt_init($conn); // to make statement variable
	mysqli_stmt_prepare($stmt, $sql); // to prepare statment with sql line
	mysqli_stmt_bind_param($stmt, "i", $user_id); // binds the parameter
	mysqli_stmt_execute($stmt); // executes sql
	$stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
	$row = mysqli_fetch_assoc($stmtresult);  // creates an associative array of the sql result
	$iv = base64_decode($row["masteriv"]); // decoded iv to binary version
	$masterkey = decryptData($row["masterkey"], $password, $iv); // decrypt masterpassword
	return $masterkey; // returns master password
}
function keyGen($conn, $password, $user_id)
{
	$iv = generateIV(); // genorates iv
	$key  = hash("sha3-512", $password); //genorates key
	$based_iv = base64_encode($iv); //base64
	$masterkey = encryptData($key, $password, $iv);
	$sql = "update user set masterkey = ?, masteriv = ? where user_id = ?;";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ssi", $masterkey, $based_iv, $user_id);
	mysqli_stmt_execute($stmt);
	return keyGet($conn, $password, $user_id);
}
/**
 * This updates the users password with the key\
 * This will roll back if it fails
 * 
 * @param mysqli $conn
 * Database connection, should be the same one mysqli_autocommit function was used on 
 * @param int $user_id
 * the user id of the user that the password is changing for
 * @param string $oldPassword
 * the old password of the user
 * @param string $newPassword
 * the new password you want the new user to use
 * @return Array<int,string>
 * The outcomes of the funciton:\
 * `[0,		"Success"]`\
 * `[1,		"Failure, Password did not meet complexity needs"]`\
 * `[2,		"Failure to change password, DBMS rolled back"]`\
 * `[3,		"Failure to change key, DBMS rolled back"]`\
 * `[4,		"Error caught by try:\n <error>, DBMS rolled back"]`\
 * `[98,	"Error caught by try:\n <error>, Unknown DBMS state"]`\
 * `[98,	"Unknown error, Unknown DBMS state"]`\
 * `[99,	"Catastrophic Failure, Unknown DBMS stat"]`
 */
function changeUserPassword($conn, $user_id, $oldPassword, $newPassword)
{
	try {
		mysqli_autocommit($conn, FALSE); // stops commits to allow rollback

		mysqli_commit($conn);// makes commit to rollback too
	} catch (Exception $e) {
		return [98, "Error caught by try:\n " . $e . ", Unknown DBMS state"];
		die("Can't change passwrod safely");
	}
	try {
		$resultFromKeyChange = keyPasswordChange($conn, $user_id, $oldPassword, $newPassword);// tries to change key to new password
		$pswdHash = password_hash($newPassword, PASSWORD_DEFAULT); //hashes the users password before it is stored
		if ($resultFromKeyChange) {
			$sql = "update user set master_password = ? where user_id = ?;";// sql to update master password
			$stmt = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($stmt, $sql);
			mysqli_stmt_bind_param($stmt, "si", $pswdHash, $user_id);
			mysqli_stmt_execute($stmt);
			$sql = "SELECT `master_password` FROM user WHERE user_id =  ?;";// sql to check new master password
			$stmt = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($stmt, $sql);
			mysqli_stmt_bind_param($stmt, "i", $user_id);
			mysqli_stmt_execute($stmt); //executes sql query
			$stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
			if ($row = mysqli_fetch_assoc($stmtresult)) {  // creates an associative array of the sql result
				if (password_verify($newPassword, $row["master_password"])) {//verifies master password has changed and works
					mysqli_commit($conn);// if all is good commit changes
					mysqli_autocommit($conn, TRUE);// enable autocommit again
					return [0, "Success"];
				} else {
					mysqli_rollback($conn);//rollback database 
					mysqli_autocommit($conn, TRUE);
					return [2, "Failure to change Passwrod, DBMS rolled back"];//tell user that the password could not be changed but account is fine
				}
			} else {
				mysqli_rollback($conn);// rollback database
				mysqli_autocommit($conn, TRUE);
				return [2, "Failure to change Passwrod, DBMS rolled back"];//rell user that the passwoudl could not be changed but account is fine
			}
		} else {
			mysqli_rollback($conn);
			mysqli_autocommit($conn, TRUE);
			return [3, "Failure to change key, DBMS rolled BACK"];
		}
	} catch (Exception $e) {
		try {
			mysqli_rollback($conn);
			mysqli_autocommit($conn, TRUE);
			return [4, "Error Caught By Try: " . $e . ", DBMS rolled back"];
		} catch (Exception $ee) {
			return [98, "Error caught by try:\n " . $ee . "\n\nAND\n\n" . $e . ", Unknown DBMS state"];// report full error and the state of database is unknown
		}
	}
	return [99, "Catastrophic Failure, Unknown DBMS stat"];// report error and that state of database is unknown
}
/**
 * This updates the key to new password\
 * **THIS DOES NOT CHANGE PASSWORD**\
 * Please use `changeUserPassword` to change Password
 * 
 * @param mysqli $conn
 * Database connection, should be the same one mysqli_autocommit function was used on 
 * @param int $user_id
 * the user id of the user that the password is changing for
 * @param string $oldPassword
 * the old password of the user
 * @param string $newPassword
 * the new password you want the new user to use
 * @return boolean
 * if it was successfully able to make changes\
 *     False - attempt failed, please rollback\
 *     True  - attempt passed, commit
 */
function keyPasswordChange($conn, $user_id, $oldPassword, $newPassword)
{
	try {
		$iv = generateIV(); // genorates iv
		$key = keyGet($conn, $oldPassword, $user_id);// gets current key
		$based_iv = base64_encode($iv); // turns IV to base64 to store
		$masterkey = encryptData($key, $newPassword, $iv);// encrypts the master key with the master password
		$sql = "update user set masterkey = ?, masteriv = ? where user_id = ?;";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ssi", $masterkey, $based_iv, $user_id);
		mysqli_stmt_execute($stmt);
		if ($key == keyGet($conn, $newPassword, $user_id)) {//tests if the key has been updated and is the same value
			return TRUE;
		} else {
			return FALSE;
		}
	} catch (ErrorException $e) {
		return FALSE;
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
		header("location:../signup.php?error=stmtfailed");
		exit();
	}
	header("location:../login.php?signup=success");
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
function getWebsiteList($conn, $user_identifier)
{
	// gets user id from either user id or auth code
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	// setup sql select statement to get all websites linked to a userr
	$sql = "SELECT website_id, website_name, web_address from user JOIN saved_website ON user.user_id = saved_website.user_id WHERE user.user_id = ? order by saved_website.website_name";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $user_id);
	mysqli_stmt_execute($stmt);
	
	$stmtresult =  mysqli_stmt_get_result($stmt);
	$result = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
	mysqli_free_result($stmtresult);
	return json_encode($result);// sending results to user
}
/**
 * checks if website with web address exists
 */
function checkIfExists($conn,$user_id,$wb_address){
	$sql = "SELECT website_id FROM `saved_website` WHERE web_address = ? and user_id = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $wb_address,$user_id);
	mysqli_stmt_execute($stmt);
	$stmtresult =  mysqli_stmt_get_result($stmt);
	$result = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
	if(sizeof($result) >= 1)// if one or more websites with the same address exists  then
		return $result[0]["webiste_id"];// send website ids
	else
		return 0;// return 0
}
function addWebsite($conn, $user_identifier, $wb_name, $wb_address)
{
	$website_name = ($wb_name);
	$website_address = ($wb_address);
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	$rand = 0;
	$available = false;
	// makes a random number webiste id and checks if it is already taken, if it is try another random number
	do {
		$rand = rand(1, 999999999);
		$sql = "SELECT 1 as 'exists' from saved_website WHERE website_id = ?";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "s", $rand);
		mysqli_stmt_execute($stmt);
		$stmtresult =  mysqli_stmt_get_result($stmt);
		$result = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
		mysqli_free_result($stmtresult);
		if (sizeof($result) > 0) {
			$available = false;
		} else if (sizeof($result) == 0) {
			$available = true;
		}
		$stmt->close();
	} while (!$available);
	//creates sql to create new website entry
	$sql = "INSERT INTO saved_website VALUES (?,?,?,?,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP())";

	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "iiss", $rand, $user_id, $website_name, $website_address);
	mysqli_stmt_execute($stmt);
	echo mysqli_stmt_error($stmt);
	$result =  mysqli_stmt_affected_rows($stmt);
	return json_encode(["result" => $result,"website_id" => $rand]);
}
function addPassword($conn, $user_identifier, $website_id, $pw_username, $pw_password, $key)
{
	// creates a initialization vector to keep passwords secure
	$iv = generateIV(); // genorates a new IV per new version of a password for securty
	$cryptUsername = encryptData($pw_username, $key, $iv);// encrypts username
	$cryptPassword = encryptData($pw_password, $key, $iv);// encrypts password
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	
	$rand = 0;
	$available = false;
	// creates a random password_id until one is not already taken
	do {
		$rand = rand(0, 999999999);
		$sql = "SELECT 1 as 'exists' from website_password WHERE password_id = ?";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "s", $rand);
		mysqli_stmt_execute($stmt);
		$stmtresult =  mysqli_stmt_get_result($stmt);
		$result = mysqli_fetch_all($stmtresult, MYSQLI_ASSOC);
		mysqli_free_result($stmtresult);
		if (sizeof($result) > 0) {
			$available = false;
		} else if (sizeof($result) == 0) {
			$available = true;
		}
		$stmt->close();
	} while (!$available);
	// adds encrypted passwords to databse
	$sql = "INSERT INTO website_password values (?,(SELECT sw.website_id FROM `saved_website` as sw WHERE sw.website_id = ? AND sw.user_id = ?),?,?,?)";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "iiisss", $rand, $website_id, $user_id, $cryptUsername, $cryptPassword, base64_encode($iv));
	mysqli_stmt_execute($stmt);
	$result =  mysqli_stmt_affected_rows($stmt);
	return json_encode(["result" => $result, "rand" => $rand, "website_id" => $website_id, "user_id" => $user_id, "cryptUn" => $cryptUsername, "cryptPw" => $cryptPassword]);
}
function getPasswordList($conn, $user_identifier, $website_id, $key)
{
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
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
	// creates new array with decrypted passwords
	for ($i = 0; $i < sizeof($cipher); $i++) {
		$result[$i] = [];
		$result[$i]["website_id"]  = $cipher[$i]["website_id"];
		$result[$i]["password_id"] = $cipher[$i]["password_id"];
		//decrypts username
		$result[$i]["username"] = decryptData($cipher[$i]["username"], $key, base64_decode($cipher[$i]["iv"]));
		//decrypts password
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
// Gets the user_id when an authentication token is used
function getUidWhereAuthCode($conn, $authToken)
{
	/**TODO:
	 * - make invalid in 2 weeks after date_created
	 * - add function to make invalid and check if invalid here
	 */
	$sql = "SELECT user_id from auth_token where auth_token = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, 's', $authToken);
	mysqli_stmt_execute($stmt);
	$stmtresult = mysqli_stmt_get_result($stmt);
	$result = mysqli_fetch_all($stmtresult);
	return $result['user_id'];
}
function deletePassword($conn, $user_identifier, $password_id)
{
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	// creates sql to delete passwrod
	$sql = "DELETE FROM website_password where password_id = ? AND password_id in (select website_password.password_id from user inner join saved_website on user.user_id = saved_website.user_id inner join website_password on saved_website.website_id = website_password.website_id WHERE user.user_id = ?) ";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ii", $password_id, $user_id);
	mysqli_stmt_execute($stmt);
	// return how many rows were affected as success value, will either be 1 or 0
	return ["success" => mysqli_stmt_affected_rows($stmt)];
}
function deleteWebsite($conn, $user_identifier, $website_id)
{
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	$sql = "DELETE FROM saved_website where website_id = ? AND user_id = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ii", $website_id, $user_id);
	mysqli_stmt_execute($stmt);
	//returns how many rows were affected as success value, will either be 1 or 0
	return ["success" => mysqli_stmt_affected_rows($stmt)];
}
function setPassword($conn, $user_identifier, $password_id, $key, $username, $password)
{
	$user_id = "";
	if ($user_identifier[0] == 0)
		$user_id = $user_identifier[1];
	else
		$user_id = getUidWhereAuthCode($conn, $user_identifier[1]);
	$iv = generateIV(); // genorates a new IV per new version of a password
	// encrypt username and password
	$cryptUsername = encryptData($username, $key, $iv);
	$cryptPassword = encryptData($password, $key, $iv);
	//updates password
	$sql = "UPDATE website_password as tb set tb.username = ?, tb.password = ?, tb.iv = ? where tb.password_id = ? AND password_id in (select website_password.password_id from user inner join saved_website on user.user_id = saved_website.user_id inner join website_password on saved_website.website_id = website_password.website_id WHERE user.user_id = ?) ";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "sssii", $cryptUsername, $cryptPassword, base64_encode($iv), $password_id, $user_id);
	mysqli_stmt_execute($stmt);
	//returns how many rows were affected as success value, will either be 1 or 0
	return ["success" => mysqli_stmt_affected_rows($stmt)];
}
// checks if password is in common passwords
function commonPassword($conn, $pD)
{
	$pD["password"] = strtolower($pD["password"]);
	$sql = "SELECT * FROM `common_passwords` WHERE `password` = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $pD["password"]);
	mysqli_stmt_execute($stmt);
	$stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
	$result = mysqli_fetch_assoc($stmtresult);
	if (count($result) >= 1) {
		return true;
	} else {
		return false;
	}
}
