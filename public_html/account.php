<?php require_once "header.php"; ?>
<script src="scripts/accountScript.js"></script>
<div class="accountDetails">
    <form class="inline-form" action="scripts/accountScript.php" method="post" id="user_details">
        <input required type="text" name="first_name" placeholder="First Name">
        <input required type="text" name="last_name" placeholder="Last Name">
        <input required type="text" name="username" placeholder="Username">
        <input required type="text" name="email" placeholder="Email">
        <input required type="date" name="dob" placeholder="Date of Birth">
        <input required type="text" name="mobile" placeholder="Mobile Number">
        <button type="button" name="submit" id="submit">Update Details</button>
    </form>
</div>