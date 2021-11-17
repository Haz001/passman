<?php session_start(["cookie_domain" => "passman.harrysy.red"]);
require "db.php";
if (isset($_SESSION["tempID"]) and !isset($_SESSION["user_id"]) and isset($_POST["otp_submit"])) { //makes sure the user is not already logged in and post data has been recieved
	$sql = "SELECT * FROM otp WHERE user_id = ? AND otp = ?"; //Queries the database if there is a otp entry for the submited otp and if it is linked to the user ID of the user logging in
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "is", $_SESSION["tempID"], $_POST["otp"]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$resulta = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) == 0) { //if no results returned is assumed to be inccorect
		header("location:../otp.php?error=otpIncorrect");
		exit();
	} else {
		if ((time() - $resulta["otp_created"]) <= 1200) { //only allows the otp if it is less than 20 mins long
			$_SESSION["user_id"] = $_SESSION["tempID"];
			unset($_SESSION["tempID"]);
			header("location:../index.php?login=success");
			exit();
		} else {
			session_unset();
			session_destroy();
			header("location:../login.php?error=otpExpired");
			exit();
		}
	}
} else {
	header("location:../index.php?error=loginError");
	exit();
}
