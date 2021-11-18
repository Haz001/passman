<?php
session_start(["cookie_domain" => "passman.harrysy.red"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/styles/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="scripts/errorHandle.js"></script>
	<title>PassMan</title>
</head>

<body>
	<div class="containerMain">
		<div class="containerMainLeft">
			<div class="logo">
				<img src="logo.png" style="width:1em;float:left;" /><span><span>Pass</span>Man</span>
			</div>
		</div>

		<div class="vl"></div>
		
		<div class="containerMainRight">
			<img id="loginIcon" src="img\profilePicture.jpeg">
			<form action="scripts/loginScript.php" method="post">
				<input type="text" name="username" placeholder="Username/Email">
				<input type="password" name="password" placeholder="Password">
				<button type="submit" name="submit">Submit</button>
			</form>
			<a href="signup.php"><p id="CreateAccount">Create Account</p></a>
			<div>
				<p id="error"></p>
			</div>
		</div>
	</div>