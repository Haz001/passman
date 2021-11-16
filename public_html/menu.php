<?php require_once "header.php";
?>
<form action="scripts/loginScript.php" method="POST">
	<input type="text" name="username" placeholder="Username/Email">
	<input type="password" name="password" placeholder="Password">
	<button type="submit" name="submit">Submit</button>
</form>
<?php require "footer.php"; ?>