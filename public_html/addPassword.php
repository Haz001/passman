<?php include 'header.php';?>

<div class="mainPage">
    <div class="mainBody">
        <div class="mainSection1" id="addMainSection1">
            <h1 class="mainSection1Title">Add new website</h1>
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
        <div class="mainSection2" id="addMainSection2">
            <h1 class="mainSection2Title">Upload CSV</h1>
            <button class="addUpload">Upload File</button>
        </div>
    </div>
</div>

<?php 
include 'footer.php';
?>