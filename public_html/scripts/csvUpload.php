

<?php

if(isset($_POST["submit"])) {
	//print_r($_FILES["fileToUpload"]);
	if(($_FILES["fileToUpload"]["type"] == "text/csv") AND ($_FILES["fileToUpload"]["size"] <= "200")){
		$lines = file($_FILES["fileToUpload"]["tmp_name"]);
		echo "File ok";
		//echo sizeof($lines);
	}
}else{
	echo('<form action="csvUpload.php" method="post" enctype="multipart/form-data">    Select file to upload:    <input type="file" name="fileToUpload" id="fileToUpload">    <input type="submit" value="Upload File" name="submit"></form>');
}
?>
