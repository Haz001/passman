<?php session_start(["cookie_domain" => "passman.harrysy.red"]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="scripts/errorHandle.js"></script>
    <link rel="icon" href="/img/DeliverIT-icon.png" type="image/png">
    <title>PassMan</title>
</head>

<body>
    <div class="otp">
        <form action="scripts/otp.php" method="POST">
            <input type="text" name="otp" placeholder="Please enter OTP">
            <button type="submit" name="otp_submit">Submit</button>
        </form>
    </div>
</body>
<div>
    <p id="error"></p>
</div>