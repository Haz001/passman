<?php
// require '../debug.php'; needs to be added
$username = strtolower("");
$password = "";

// require './hash.php';
require "../pwd/mysql.php";
$dsn = "mysql:host=$sqlHost;dbname=$sqlDatabase";
$sqlConn = new PDO($dsn, $sqlUsername, $sqlPassword);
$sql = "SELECT * FROM users WHERE username = :username";
$statement = $sqlConn->prepare($sql);
$statement->bindParam(':username', $username, PDO::PARAM_STR);
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
$passwordhash = password_hash($password, PASSWORD_DEFAULT);
if (count($rows) == 1)
{
	if (
		($rows[0]["username"] == $username)
		and
		($rows[0]["password"] == $passwordhash)
	)
	{
		echo "Result: Logged in as " . $rows[0]["username"];
		// this will be turned into function and used elsewhere
	}
	else
	{
		echo "Result: Could not authenticate";
	}
}
else
{
	echo "Result: Could not authenticate";
}
//elseif (($action == "search") and $debug) {
//	echo "Result:<pre>".htmlspecialchars(var_export($rows,true))."</pre>";
//	//print_r(str_replace("\n","<br/>",var_export($rows,true)));
