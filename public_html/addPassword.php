<?php include 'header.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/styles/cssOskar.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="scripts/errorHandle.js"></script>
	<title>PassMan</title>
</head>

<body class="addPage">
    <div class="addMain">
        <div class="addNewWebsite">
            <h1 class="addTitle">Add new website</h1>
            <form action="" method="POST">
                <label for="websiteName">Website Name</label>
                <input type="text" id="websiteName" name="websiteName" required>
                <label for="websiteUsername">Username</label>
                <input type="text" id="websiteUsername" name="websiteUsername" required>
                <label for="websitePassword">Password</label>
                <input type="password" id="websitePassword" name="websitePassword" required>
                <label for="websiteAddress">Website Address</label>
                <input type="text" id="websiteAddress" name="websiteAddress" required>
                <input class="addUpload2" type="submit" value="Add">
            </form>
        </div>
        <div class="verticalLine">
        </div>
        <div class="uploadNewWesbite">
            <h1 class="addTitle">Upload CSV</h1>
            <button class="addUpload">Upload File</button>
        </div>
    </div>
</body>
</html>

