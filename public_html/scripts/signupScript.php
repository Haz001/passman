<?php
if (isset($_POST["submit"])) { // checks if any post data has been received
    require_once("db.php");
    require_once("functions.php"); // calls both required php scripts
    $pD = $_POST;
    unset($pD["submit"]);
    $pD = array_map('htmlentities', $pD);
    if (!emptyFields($pD)) { // checks if any of passed fields are empty
        header("location:../signup.php?error=ef");
        exit();
    }
    if (isUnique($conn, $pD) !== false) { //checks if the username and email are both unique to the database
        header("location:../signup.php?error=ue");
        exit();
    }

    signUp($conn, $pD); //signs up the user
} else {
    header("location../index.php");
    exit();
}
