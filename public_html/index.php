<?php require_once "header.php";
if (!isset($_SESSION["user_id"])) {
	header("location: login.php");
	exit();
} else {
	header("location: main.php");
	exit();
}
?>
<!--<div>
	<img src="logo.png" /><span>PassMan</span>
</div>
<form>
	<ul>
		<li><a href="login.php">Login</a></li>
		<li><a href="signup.php">Signup</a></li>
	</ul>
</form>-->