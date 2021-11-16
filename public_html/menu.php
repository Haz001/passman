<?php //require_once "header.php";
//?>
<?php

session_start(["cookie_domain" => "passman.harrysy.red"]);
echo $_SESSION["user_id"];
echo $_COOKIE["key"];
?>
<?php require "footer.php"; ?>