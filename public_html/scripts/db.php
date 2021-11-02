<?php
$sqlservername = "localhost";
$sqlusername = "passroot";
$sqlpassword = "Genetics-Rockband-Radiated-Ahead";
$sqldbname = "passman";

// Create connection
$conn = mysqli_connect($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
