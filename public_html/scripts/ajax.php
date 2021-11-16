<?php
require_once "functions.php";
require_once "db.php";
if(isset($_GET["get"])){
    $get = $_GET["get"];
}
echo $_GET;
if($get == "websites"){
    echo getWebsiteList($conn);
}
