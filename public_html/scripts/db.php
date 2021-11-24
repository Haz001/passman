<?php
require "/var/www/passman/public_html/pwd/mysql.php"; // grabbing sql loggins from one place
$conn = mysqli_connect($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase); // using centeral logins
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
