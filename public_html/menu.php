<?php //require_once "header.php";
//?>
<?php
session_start(["cookie_domain" => "passman.harrysy.red"]);
$uid = $_SESSION["user_id"];
$key = $_COOKIE["key"];


?>
<?php require "footer.php"; ?>