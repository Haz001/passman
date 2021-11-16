<?php //require_once "header.php";
//?>
<?php

session_start(["cookie_domain" => "passman.harrysy.red"]);
echo $_SESSION["user_id"];
setcookie("likeiknow","fuck",0,"","passman.harrysy.red",true);
echo $_COOKIE[""];
?>
<?php require "footer.php"; ?>