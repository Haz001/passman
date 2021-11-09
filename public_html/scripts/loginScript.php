<?php
if (isset($_POST["submit"])) { // checks if any post data has been received
    require_once("db.php");
    require_once("functions.php"); // calls both required php scripts
    $pD = $_POST;
    $pD["email"] = $pD["username"];
    unset($pD["submit"]);
    $pD = array_map('htmlentities', $pD);
    if (!emptyFields($pD)) { // checks if any of passed fields are empty
        header("location:../login.php?error=ef");
        exit();
    }
    //More checks to be done
    if (!passwordComplex($pD["password"])) {
        header("location:../index.php?error=pswdcomplexity");
        exit();
    }
    loginUser($conn, $pD); //logs in the user


} else {
    header("location:../index.php");
    exit();
}
