<?php
require_once "functions.php";
require_once "db.php";
if (isset($_POST["location"]) && $_POST["location"] == "extension") {
    $pD = $_POST;
    $pD["email"] = $pD["username"];
    unset($pD["submit"]);
    $pD = array_map('htmlentities', $pD);
    if (!emptyFields($pD)) { // checks if any of passed fields are empty
        response("error", "ef");
    }
    //More checks to be done
    loginUser($conn, $pD); //logs in the user
} else {
    http_response_code(400);
    die("Malformed Query");
}

