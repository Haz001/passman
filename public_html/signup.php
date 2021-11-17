<?php require_once "header.php"; ?>

<form action="scripts/signupScript.php" method="POST">
	<input type="text" name="first_name" placeholder="First Name">
	<input type="text" name="last_name" placeholder="Last Name">
	<input type="text" name="username" placeholder="Username">
	<input type="text" name="email" placeholder="Email">
	<input type="text" name="dob" placeholder="Date of Birth">
	<input type="text" name="mobile" placeholder="Mobile Number">
	<input type="password" name="password" placeholder="Password">
	<button type="submit" name="submit">Submit</button>
</form>


<div>
	<p id="error"></p>
</div>