<?php include 'header.php';?>

<div class="mainPage">
    <div class="mainBody">
        <div class="mainSection1">
            <h1 class="mainSection1Title">Website Name</h1>
            <input type="search" class="mainSearch" value="Search">
            <button class="mainAddImport">Add/Import</button>
            <button class="websiteName">Amazon</button>
            <button class="websiteName">Netflix</button>
            <button class="websiteName">YouTube</button>
            <button class="websiteName">Twitch</button>
            <button class="loadMoreButton">Load More Results</button>
        </div>
        <div class="verticalLine">
        </div>
        <div class="mainSection2"  id="mainSection2">
            <div class="mainUserName">
                <button class="name">Harry</button>
                <button class="name">Sam</button>
                <button class="name">Rogan</button>
            </div>
            <div class="mainUserDetails" id="mainWebsiteSection">
                <h1 class="mainSection2Title">Amazon</h1>
                <h4>Username</h4>
                <input type="text">
                <h4>Password</h4>
                <input type="text">
                <h4>Website Address</h4>
                <input type="text">
                <div class="mainUserDetailsButtons">
                    <button class="editButton">Edit</button>
                    <button class="deleteButton">Delete</button>
                </div>
               
            </div>
        </div>
    </div>
</div>
<?php 
include 'footer.php';
?>
