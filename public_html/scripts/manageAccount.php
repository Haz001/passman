<?php
require_once "db.php";
require_once "functions.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
if (isset($_SESSION["user_id"])) {
    if ($_POST["request"] == "all") {
        $sql = "SELECT `first_name`, `last_name`, `username`, `email`, `dob`, `mobile` FROM `user` WHERE `user_id` = ? ";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_assoc($result);
        if ($result == null) {
            http_response_code(404);
            die("No Data");
        } else {
            echo json_encode($result);
            exit();
        }
    } elseif ($_POST["request"] == "update") {
        $pD = array_map('htmlentities', $_POST);
        if (!emptyFields($pD)) {
            echo json_encode(array("result" => "error", "error" => "ef"));
            exit();
        }
        $sql = "UPDATE `user` SET `first_name` = ?,  `last_name` = ?, `username` =?,`email` = ?, `dob` = ?, `mobile` = ? WHERE `user_id` = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $pD["first_name"], $pD["last_name"], $pD["username"], $pD["email"], $pD["dob"], $pD["mobile"], $_SESSION["user_id"]);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(array("result" => "success"));
            exit();
        } else {
            echo json_encode(array("result" => "error"));
            exit();
        }
    } elseif ($_POST["request"] == "delete") {
        $sql = "DELETE FROM `user` WHERE `user_id` = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $_SESSION["user_id"]);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(array("result" => "success"));
            exit();
        } else {
            echo json_encode(array("result" => "error"));
            exit();
        }
    } else {
        echo json_encode(array("result" => "error", "error" => "selection not understood"));
        exit();
    }
} else {
    echo json_encode("error1");
}
