<?php require_once "header.php";?>
<?php
$state = 0;
if($_SERVER['REQUEST_METHOD'] == "GET"){
	$state = 0;
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST")
{
	if(isset($_POST['username'])){
		$state = 1;
	}
	else{
		$state = 0;
	}
}
$page = "";
if($state == 0){
	$page.="<span class=\"title\">Sign In</span>";
	$page.="<span class=\"subTitle\">Use your PassMan Account</span>";
	$page.="<span class=\"label\">Username:</span>";
	$page.="<input type=\"text\" name=\"username\" placeholder=\"Username\"/>";
	$page.="<input type=\"submit\" name=\"submitun\" value=\"Next\"/>";
}elseif($state == 1){
	$username = strtoLower($_POST["username"]);
	echo $username;
	require "pwd/mysql.php";
	$dsn = "mysql:host=$sqlHost;dbname=$sqlDatabase";
	$sqlConn = new PDO($dsn, $sqlUsername, $sqlPassword);
	$sql = "SELECT first_name, last_name FROM user WHERE username = :username";
	$statement = $sqlConn->prepare($sql);
	$statement->bindParam(':username', $username, PDO::PARAM_STR);
	$statement->execute();
	$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
	print_r($rows);
	if (count($rows) == 1)
	{
		$page.="<span class=\"title\">Welcome Back</span>";
		$page.="<span class=\"subTitle\">".$rows[0]["first_name"]." ".$rows[0]["last_name"]."</span>";
		$page.="<span class=\"label\">Password:</span>";
		$page.="<input type=\"password\" name=\"password\" placeholder=\"Password\"/>";
		$page.="<input type=\"submit\" name=\"submitun\" value=\"Next\"/>";
	}
	$sqlConn = null;
}
 ?>

<div>

	<form action="login.php" method="post">
		<div>
			<img src="logo.png"/> <span>PassMan</span>
		</div>
		<?php print($page);?>
	</form>
</div>
<?php require "footer.php"; ?>
