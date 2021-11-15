<?php require_once "header.php";
?>
<style>
	.title {
		/*! display: block; */
		font-size: larger;
	}

	span {
		display: block;
		/*! align-content: center; */
		text-align: center;
		width: 100%;
	}

	form {
		display: block;
		overflow: hidden;
		width: 20rem;
		background: white;
		margin: auto;
		border-radius: 1rem;
		height: 30rem;
		margin-top: calc(50vh - 15rem);
		box-shadow: 1rem 1rem 0.5rem grey;
		border-style: solid;
		padding: 0.5rem;
	}

	.label {
		/*! width: auto; */
		/*! overflow: visible; */
		/*! display: inline-block; */
		text-align: left;
	}

	input[type="submit"] {
		/*! color: red; */
		right: 1rem;
		bottom: 1rem;
		/*! position: ; */
		font-size: 1.25rem;
		background: #0050EF;
		color: white;
		border-style: solid;
		border-color: #0050EF;
		border-width: 0.5rem;
		border-radius: 0.5rem;
	}

	input[type="password"] {
		font-size: 1.25rem;
	}
</style>

<?php
require_once("scripts/db.php");
require_once("scripts/functions.php");
$state = 0;
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	$state = 0;
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['username'])) {
		$state = 1;
	} else {
		$state = 0;
	}
}
$page = "";
if ($state == 0) {
	$page .= "<span class=\"title\">Sign In</span>";
	$page .= "<span class=\"subTitle\">Use your PassMan Account</span>";
	$page .= "<span class=\"label\">Username:</span>";
	$page .= "<input type=\"text\" name=\"username\" placeholder=\"Username\"/>";
	$page .= "<input type=\"submit\" name=\"submitun\" value=\"Next\"/>";
} elseif ($state == 1) {
	$username = strtoLower($_POST["username"]);
	require "pwd/mysql.php";
	$dsn = "mysql:host=$sqlHost;dbname=$sqlDatabase";
	$sqlConn = new PDO($dsn, $sqlUsername, $sqlPassword);
	$sql = "SELECT first_name, last_name FROM user WHERE username = :username";
	$statement = $sqlConn->prepare($sql);
	$statement->bindParam(':username', $username, PDO::PARAM_STR);
	$statement->execute();
	$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
	if (count($rows) == 1) {
		$page .= "<span class=\"title\">Welcome Back</span>";
		$page .= "<span class=\"subTitle\">" . $rows[0]["first_name"] . " " . $rows[0]["last_name"] . " Please enter in your password.</span>";
		$page .= "<span class=\"label\">Password:</span>";
		$page .= "<input type=\"password\" name=\"password\" placeholder=\"Password\"/>";
		$page .= "<input type=\"submit\" name=\"submitun\" value=\"Next\"/>";
	}
	$sqlConn = null;
}
?>

<div>

	<form action="login.php" method="post">
		<div>
			<img src="logo.png" /> <span>PassMan</span>
		</div>
		<?php print($page); ?>
	</form>
</div>
<?php require "footer.php"; ?>