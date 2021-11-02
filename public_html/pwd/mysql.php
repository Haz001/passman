<?php
// Here are the MySQL credentials
// to add them to PHP script just
// add:
// require "./pwd/mysql.php";
// to the code and then use the variables:
$sqlUsername = "passroot";
$sqlPassword = "Genetics-Rockband-Radiated-Ahead";
$sqlHost = "localhost"; // same machine
//$sqlPort = ""; // Default
$sqlDatabase = "passman";
// to connect to the locally
// hosted MySQL server
//
// This below is to hide this
// page from any prying eyes if
// nginx has error and doesn't
// hide this directory:
http_response_code(404);// stop silly bots from indexing this page
include "/usr/share/nginx/html/index.html";// adds HTML to page, shoudl be change this to 404 error later to hide this page
?>
