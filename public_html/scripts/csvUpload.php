

<?php

if(isset($_POST["submit"])) {
    print_r($_FILES["fileToUpload"]);
    print_r(readfile($_FILES["fileToUpload"]["tmp_name"]));
}else{
    echo('<form action="csvUpload.php" method="post" enctype="multipart/form-data">    Select file to upload:    <input type="file" name="fileToUpload" id="fileToUpload">    <input type="submit" value="Upload File" name="submit"></form>');
}
?>