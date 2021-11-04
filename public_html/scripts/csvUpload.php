<?php
if(isset($_POST["submit"])) {
<<<<<<< HEAD
	//print_r($_FILES["fileToUpload"]);
	if(($_FILES["fileToUpload"]["type"] == "text/csv") AND ($_FILES["fileToUpload"]["size"] <= "200")){
		$lines = file($_FILES["fileToUpload"]["tmp_name"]);
		echo "File ok";
		//echo sizeof($lines);
	}
=======
    print_r($_FILES["fileToUpload"]);
    print_r(readfile($_FILES["fileToUpload"]["tmp_name"]));
>>>>>>> c16e33f2237994827f3ffb402998507be2c4355d
}else{
	echo('<form action="csvUpload.php" method="post" enctype="multipart/form-data">    Select file to upload:    <input type="file" name="fileToUpload" id="fileToUpload">    <input type="submit" value="Upload File" name="submit"></form>');
}
<<<<<<< HEAD
?>
=======
>>>>>>> c16e33f2237994827f3ffb402998507be2c4355d
