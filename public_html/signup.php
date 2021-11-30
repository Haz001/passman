<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/styles/styles.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="scripts/errorHandle.js"></script>
	<title>PassMan</title>
</head>

<div class="containerMain">
	<div class="containerMainLeft">
		<div class="logo">
			<img src="logo.png" style="width:1em;float:left;" /><span><span>Pass</span>Man</span>
		</div>
	</div>

	<div class="vl"></div>

	<div class="containerMainRight">
		<img id="loginIcon" src="img\profilePicture.jpeg">

		<form action="scripts/signupScript.php" method="POST">
			<input type="text" name="first_name" placeholder="First Name">
			<input type="text" name="last_name" placeholder="Last Name">
			<input type="text" name="username" placeholder="Username">
			<input type="text" name="email" placeholder="Email">
			<input type="date" name="dob" placeholder="Date of Birth">
			<input type="text" name="mobile" placeholder="Mobile Number">
			<input type="password" name="password" placeholder="Password">
			<button type="submit" name="submit">Submit</button>
		</form>

		<div>
			<p id="error"></p>
		</div>
	</div>
</div>