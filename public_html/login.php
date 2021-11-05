<?php require_once "header.php";?>
<?php
$state = 0;
echo $_SERVER['REQUEST_METHOD'];
if($_SERVER['REQUEST_METHOD'] == "GET"){
	$state = 0;
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST")
{

}
$page = [];
if($state == 0){
	$page["title"] => "Sign In";
	$page["subTitle"] =>"Use your PassMan Account";
	$page["label"] =>"Username";
	$page["inputType"] => "text";
	$page["inputName"] => "username"
}
 ?>
<div>

	<form action="login.php">
		<div>
			<img src="logo.png"/> <span>PassMan</span>
		</div>
		<span class="titel"><?php print($title)?></span>
		<span class="subTitle"><?php print($subtitle)?></span>

	</form>
</div>
<?php require_once "footer.php";?>
