<?php

require_once "functions.php";
require_once "db.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
if (isset($_SESSION["user_id"]) && !isset($_GET["auth_token"])) {

	$uid = $_SESSION["user_id"];
	$key = $_COOKIE["key"];
	$result = [];
	if (isset($_GET["get"])) {
		$get = $_GET["get"];
		if ($get == "websites") {
			$result[0] = getWebsiteList($conn, $uid);
			$result[1] = 200;
		}
		else if ($get == "passwords") {
			if (isset($_GET["website_id"])) {
				$result[0] = getPasswordList($conn, $uid, $_GET["website_id"], $key);
				$result[1] = 200;
			}
		}
		else {
			$result[0] = $_GET["get"] . " command not found";
			$result[1] = 403;
		}
	}
	else if(isset($_POST["update"]))
	{
		if($_POST["update"] == "password"){
			$result[0] = json_encode([
				"result"=>setPasswordList($conn,$uid,$_POST["password_id"],$key,$_POST["username"],$_POST["password"]),
				"uid"=>$uid,
				"key" => $key
			]);
			$result[1] = 420;
		}
	}
	if (($result[0] == "") || ($result[0] == null) || ($result[0] == "null")||($result[0] == "[]")) {
		$result[0] = "No data found";
		$result[1] = 204;
	}
	echo $result[0];
	http_response_code($result[1]);
} elseif (isset($_GET["username"])) {
	$ar = array("1" => "hello");
	echo json_encode($ar);
} else {
	echo "To use Ajax please login with is required";
	http_response_code(401);
}
