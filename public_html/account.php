<?php require_once "header.php";
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
}
?>
<script src="scripts/accountScript.js"></script>
<h1 style="text-align: center;padding: 0.5em;">Account Details</h1>
<div class="accountDetails">
    <form class="inline-form" action="scripts/accountScript.php" method="post" id="user_details">
        <input required type="text" name="first_name" placeholder="First Name">
        <input required type="text" name="last_name" placeholder="Last Name">
        <input required type="text" name="username" placeholder="Username">
        <input required type="text" name="email" placeholder="Email">
        <input required type="date" name="dob" placeholder="Date of Birth">
        <input required type="text" name="mobile" placeholder="Mobile Number">
        <button type="button" name="submit" id="submit">Update Details</button>
        <button id="switch">Change Password</button>
    </form>
    <form class="inline-form" id="passform" style="display: none">
        <input required type="password" name="oldPassword" placeholder="Current Password">
        <input required type="password" name="newPassword" placeholder="New Password">
        <button type="button" id="submitPass">Update Password</button>
    </form>
    <div>
        <p id="output"></p>
    </div>
    <div class="ad">
        <button type="button" id="delete">Delete Account</button>
    </div>
</div>