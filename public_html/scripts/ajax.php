<?php
require_once "functions.php";
require_once "db.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
$uid = $_SESSION["user_id"];
$key = $_COOKIE["key"];
$result = "";
if(isset($_GET["get"])){
    $get = $_GET["get"];
}
if($get == "websites"){
    $result = getWebsiteList($conn,$uid);
}else{
    echo $_GET["get"]." command not found";
    http_response_code(500);
    exit();
}
if(($result == "")||($result == null)||($result == "null")){
    echo "No data found";
    http_response_code(444);
}else{
    echo $result;

    exit();
}