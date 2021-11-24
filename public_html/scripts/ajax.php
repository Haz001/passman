<?php

require_once "functions.php";
require_once "db.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
if (isset($_SESSION["user_id"]) && !isset($_GET["auth_token"])) {

	$uid = $_SESSION["user_id"];
	$key = $_COOKIE["key"];
	$result = "";
	if (isset($_GET["get"])) {
		$get = $_GET["get"];
		if ($get == "websites") {
			$result = getWebsiteList($conn, $uid);
		}
		else if ($get == "passwords") {
			if (isset($_GET["website_id"])) {
				$result = getPasswordList($conn, $uid, $_GET["website_id"], $key);
			}
		}
		else {
			echo $_GET["get"] . " command not found";
			http_response_code(403);
			exit();
		}
	}
	else if(isset($_POST["update"]))
	{
		if($_POST["update"] == "password"){
			$result = (string) $_POST["username"] . (string) $_POST["password"];
			http_repsonse_code(501);
		}
	}
	if (($result == "") || ($result == null) || ($result == "null")||($result == "[]")) {
		echo "No data found";
		http_response_code(444);
	} else {
		echo $result;

		exit();
	}
} elseif (isset($_GET["username"])) {
	$ar = array("1" => "hello");
	echo json_encode($ar);
} else {
	http_response_code(401);
}
