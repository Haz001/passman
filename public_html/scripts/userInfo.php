<?php session_start(["cookie_domain" => "passman.harrysy.red"]);
require_once "db.php";
if (isset($_SESSION["user_id"])) {
	$sql = "SELECT first_name, last_name FROM user WHERE user_id = ?";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
	mysqli_stmt_execute($stmt);
	$rows = mysqli_stmt_get_result($stmt);
	$rows = mysqli_fetch_assoc($rows);
	echo json_encode($rows);
} else {
	http_response_code(403);
	die('Forbidden');
}
