<?php
require "../pwd/mysql.php"; // grabbing sql loggins from one place
//$sqlservername = "localhost";
// $sqlusername = "passroot";
// $sqlpassword = "Genetics-Rockband-Radiated-Ahead";
// $sqldbname = "passman";

// Create connection
// $conn = mysqli_connect($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
$conn = mysqli_connect($sqlHost,$sqlUsername,$sqlPassword,$sqlDatabase)// using centeral logins
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
