<?php
require_once "scripts/functions.php";
require_once "scripts/db.php";
session_start(["cookie_domain" => "passman.harrysy.red"]);
/**
 * @param string[] $head
 * The column header of the CVS
 * @return bool
 * If its valid or not
 */
function csvValidator($head){
	if(sizeof($head) >= 3)
		if(in_array("url",$head)||in_array("login_uri",$head))
			if(in_array("username",$head)||in_array("login_username",$head))
				if(in_array("password",$head)||in_array("login_password",$head))
					return TRUE;
	return FALSE;
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$f = file_get_contents($_FILES["passwordCsv"]["tmp_name"]);
	$s = $_FILES["passwrodCsv"]["size"];
	/** @var array<array<string,string>> */
	$array = array_map("str_getcsv", explode("\n", $f));
	if((sizeof($array)>1) && csvValidator($array[0])){
		if(536870912 >= $s){
			$head = $array[0];
			$noName = in_array("name",$head);
			$tmpPwd = [];
			for ($i = 1;$i < sizeof($array);$i++){
				$row = $array[$i];
				for ($j = 0;$j < min(sizeof($head),sizeof($row));$j++){
					
					if(($head[$j] == "url")||($head[$j] == "login_uri"))
						$tmpPwd[$i]["url"] = $row[$j];
					else if($head[$j] == "name")
						$tmpPwd[$i]["name"] = $row[$j];
					else if(($head[$j] == "username")||($head[$j] == "login_username"))
						$tmpPwd[$i]["username"] = $row[$j];
					else if(($head[$j] == "password")||($head[$j] == "login_password"))
						$tmpPwd[$i]["password"] = $row[$j];
				}
			}
			$count = 0;

			for ($i = 0;$i < sizeof($tmpPwd);$i++){

				if(isset($tmpPwd[$i]["username"]) && isset($tmpPwd[$i]["password"])){
					$ifExists = checkIfExists($conn,$_SESSION["user_id"],$tmpPwd[$i]["url"]);
					if($ifExists != 0){
						addPassword($conn,$_SESSION["user_id"],$ifExists,$tmpPwd[$i]["username"],$tmpPwd[$i]["password"],$_COOKIE["key"]);
						echo "Added ".$tmpPwd[$i]["username"]." - ".$tmpPwd[$i]["password"].$tmpPwd[$i]["url"];
					}else{
						$websiteId = "";
						if($noName)
							$websiteId = addWebsite($conn,$_SESSION["user_id"],$tmpPwd[$i]["url"],$tmpPwd[$i]["url"]);
						else
							$websiteId = addWebsite($conn,$_SESSION["user_id"],$tmpPwd[$i]["name"],$tmpPwd[$i]["url"]);
						echo "Added website ".$tmpPwd[$i]["url"];
						addPassword($conn,$_SESSION["user_id"],$websiteId,$tmpPwd[$i]["username"],$tmpPwd[$i]["password"],$_COOKIE["key"]);
						echo "Added ".$tmpPwd[$i]["username"]." - ".$tmpPwd[$i]["password"].$tmpPwd[$i]["url"];
					}
					$count++;
				}	
			}
			echo "Added ".$count." of ".sizeof($tmpPwd);
		}else{
			die("<p><h1>Error:</h1></p><br/><a href=\"/upload.php\">Go back and try again</a>");
		}

	}else{
		die("<p><h1>Error:</h1>Wrong Format, the CVS provided is not formated correctly or is incompatible at the current time. Current supported formates are 'Chrome', 'Firefox', 'BitWarden'</p><br/><a href=\"/upload.php\">Go back and try again</a>");
	}
	
}
else{
echo '
	<form method="post" action="/upload.php" enctype="multipart/form-data" >
	<h1>Upload your old passwords:</h1>
	<input type="file" name="passwordCsv" accept="text/csv,.csv">
	<input type="submit" value="submit CSV"/>
	</form>
	';
}