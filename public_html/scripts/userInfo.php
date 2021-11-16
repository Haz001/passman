<?php session_start(["cookie_domain" => "passman.harrysy.red"]);
require_once "db.php";
if (isset($_POST["submit"]) && isset($_SESSION["user_id"])) {
    $sql = "SELECT first_name, last_name FROM user WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
} else {
    http_response_code(403);
    die('Forbidden');
}
