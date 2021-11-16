<link rel="stylesheet" href="/styles/styles.css">

<div class="containerMain">
	<div class="containerMainLeft">
		<div>
			<img src="logo.png"/><span>Pass</span>Man
		</div>
	</div>
	<div class="containerMainLeft">
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