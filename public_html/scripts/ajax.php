<?php
require_once "functions.php";
require_once "db.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
if(isset($_GET["get"])){
    $get = $_GET["get"];
}
if($get == "websites"){
    echo "Websites:";
    echo getWebsiteList($conn,$_SESSION["user_id"]);
}else{
    echo $_GET["get"]."Command not found";
}
