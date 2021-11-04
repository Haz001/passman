<form action="csvUpload.php" method="post">
    <input type="file" name="file"/>
    <input type="submit">
</form>
<?php
print_r($_POST("file"));
