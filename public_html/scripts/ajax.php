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
			$result[0] = getWebsiteList($conn, [0,$uid]);
			$result[1] = 200;
		}
		else if ($get == "passwords") {
			if (isset($_GET["website_id"])) {
				$result[0] = getPasswordList($conn, [0,$uid], $_GET["website_id"], $key);
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
			$result[0] = json_encode(setPassword($conn,[0,$uid],$_POST["password_id"],$key,$_POST["username"],$_POST["password"]));
			$result[1] = 420;
		}
	}
	else if (isset($_POST["add"]))
	{
		if ($_POST["add"] == "website")
		{
			$result[0] = addWebsite($conn,[0,$uid],$_POST["website_name"],$_POST["website_address"]);
			$result[1] = 200;
		}
		else if ($_POST["add"] == "password")
		{
			$result[0] = addPassword($conn,[0,$uid],$_POST["website_id"],$_POST["username"],$_POST["password"],$key);
			$result[1] = 200;
		}
	}
	else if (isset($_POST["delete"]))
	{
		if ($_POST["delete"] == "website")
		{
			//$result[0] = addWebsite($conn,[0,$uid],$_POST["website_id"]);
			$result[1] = 200;
		}
		else if ($_POST["delete"] == "password")
		{
			$result[0] = json_encode(deletePassword($conn,[0,$uid],$_POST["password_id"]));
			$result[1] = 200;
		}
	}

	if (($result[0] == "") || ($result[0] == null) || ($result[0] == "null")||($result[0] == "[]")) {
		echo "No data found";
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
