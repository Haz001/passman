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
			<div>
				<img src="logo.png" /><span>Pass</span>Man
			</div>
		</div>
		<div class="containerMainRight">
			<form action="scripts/loginScript.php" method="POST">
				<input type="text" name="username" placeholder="Username/Email">
				<input type="password" name="password" placeholder="Password">
				<button type="submit" name="submit">Submit</button>
			</form>
			<div>
				<p id="error"></p>
			</div>
		</div>

	</div>