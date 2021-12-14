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
	<script src="scripts/userInfo.js"></script>
	<script src="scripts/errorHandle.js"></script>
	<title>PassMan</title>
</head>

<body>
	<header>
		<div class="navbar">
			<div class="emptyDiv"></div>
			<a href="index.php">
				<h1 class="headerTitle"><span>Pass</span>Man</h1>
			</a>
			<div class="section2">
				<h3 class="headerMessage" id="name"></h3>
				<img class="headerImage" src="/img/profilePicture.jpeg" alt="profile pic">
				<div class="accountDrop">
					<a href="account.php">Account</a>
					<a href="settings.php">Settings</a>
					<a href="logout.php">Logout</a>
				</div>
			</div>
		</div>
	</header>