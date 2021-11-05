<?php

function emptyFields($toSearch): bool
{
    $rt = true;
    foreach ($toSearch as $value) { //Searches each value in $toSearch and returns false if any empty values are found
        if (empty($value)) {
            $rt = false;
        }
    }
    return $rt;
}

function isUnique($conn, $pD)
{
    $sql = "SELECT * FROM user WHERE username = ? OR  email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) { //checks if statement prepares correctly
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $pD["username"], $pD["email"]);
    mysqli_stmt_execute($stmt); //executes sql query
    $stmtresult = mysqli_stmt_get_result($stmt); //gets the result of the sql query
    if ($row = mysqli_fetch_assoc($stmtresult)) {  // creates an associative array of the sql result
        return $row;
    } else {
        $stmtresult = false;
        return $stmtresult;
    }
    mysqli_stmt_close($stmt);
}

function loginUser($conn, $pD)
{
    $userInfo = isUnique($conn, $pD); //uses the isUnique function to check if user exists and to get user details
    // from database
    if ($userInfo == false) {
        header("location:../login.php?error=notfound");
        exit();
    }
    if (password_verify($pD["password"], $userInfo["password"])) {
        //checks if the password hash inputted and the password
        //hash on the database match, the session is then setup
        session_start();
        $_SESSION["userID"] = $userInfo["userID"];
        $_SESSION["first_name"] = $userInfo["first_name"];
        $_SESSION["last_name"] = $userInfo["last_name"];
        $_SESSION["username"] = $userInfo["username"];
        $_SESSION["email"] = $userInfo["email"];
        $_SESSION["password"] = $userInfo["password"];

        $_SESSION["iv"] = $userInfo["iv"];
        header("location:../index.php?error=success");
        exit();
    } else {
        header("location:../login.php?error=notFound");
        exit();
    }
}

function signUp($conn, $pD)
{
    $sql = "INSERT INTO user (first_name, last_name, username, email, password, dob, mobile, iv) VALUES (?,?,?,?,?,?,?,?);"; //starts to
    // prepare the sql statement
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    $pswdHash = password_hash($pD["password"], PASSWORD_DEFAULT); //hashes the users password before it is stored

    mysqli_stmt_bind_param($stmt, "ssssssss", $pD["first_name"], $pD["last_name"], $pD["username"], $pD["email"], $pswdHash, $pD["dob"], $pD["mobile"], generateIV());
    //bind parameters to statement
    if (!mysqli_stmt_execute($stmt)) { //executes the INSERT statement
        header("location:../signup.php?error=stmtfailed");
        exit();
    }
    header("location:../signup.php?error=success");
    exit();
}
function generateIV()
{
    $cipher = "aes-256-cbc"; //define cipher to use
    $ivlen = openssl_cipher_iv_length($cipher); //defines iv's length
    $iv = openssl_random_pseudo_bytes($ivlen); //creates iv using random bytes

    return $iv;
}

function encryptData($data, $key, $iv)
{
    $cipher = "aes-256-cbc"; //define cipher to use
    if (in_array($cipher, openssl_get_cipher_methods())) { //checks if cipher is valid
        $ciphertext = openssl_encrypt($data, $cipher, $key, $options = 0, $iv); //encrypts 
        return $ciphertext;
    }
    return -1;
}

function decryptData($ciphertext, $key, $iv)
{
    $cipher = "aes-256-cbc"; //define cipher to use
    if (in_array($cipher, openssl_get_cipher_methods())) { //checks if cipher is valid
        $plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv); //decrypts
        return $plaintext;
    }
    return -1;
}
