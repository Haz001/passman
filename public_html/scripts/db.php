<?php
$sqlservername = "db5005610247.hosting-data.io";
$sqlusername = "dbu988655";
$sqlpassword = "k6fQE5qU8P_GX3c";
$sqldbname = "dbs4720873";

// Create connection
$conn = mysqli_connect($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
