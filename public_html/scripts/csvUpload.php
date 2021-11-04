

<?php

if(isset($_POST["submit"])) {
    print_r($_FILES["fileToUpload"]);
}else{
    echo('<form action="csvUpload.php" method="post" enctype="multipart/form-data">    Select file to upload:    <input type="file" name="fileToUpload" id="fileToUpload">    <input type="submit" value="Upload File" name="submit"></form>');
}
?>