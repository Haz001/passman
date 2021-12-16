<?php require_once "header.php";
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
} ?>

<div class="mainPage">
    <div class="mainBody">
        <div class="mainSection1">
            <h1 class="mainSection1Title">Website Name</h1>
            <!--<input type="search" class="mainSearch" value="Search">-->
            <button id="mainAddImport" class="mainAddImport">Add</button>
			<button onclick="refresh()" href="#">Refresh</button>
            <div id="list">
            </div>

        </div>
        <div class="verticalLine">
        </div>
        <div class="mainSection2" id="mainSection2">
            <div id="mainUserButtons" class="mainUserName">
				<div id="accounts">
				</div>
				<div style="width:100%;display:flex;">
					<button onclick="makeOverlayPassword()" class="name">add</button>
					<button onclick="refresh()" class="name">Refresh</button>
				</div>
            </div>
            <div class="mainUserDetails" id="mainWebsiteSection">
                <a id="mainWebsiteLink">
                    <h1 id="mainWebsiteTitle" class="mainSection2Title">Example.com</h1>
                </a>
                <div id="passwords">
                    <h4>Username</h4>
                    <input disabled id="passwordUsername" type="text" value="Username">
                    <h4>Password</h4>
                    <input disabled id="passwordPassword" type="text" value="Password">
                    <!--<h4>Website Address</h4>
                    <input type="text">-->
                    <div class="mainUserDetailsButtons">
                        <button id="passwordEdit" class="editButton">Edit</button>
                        <button id="passwordDelete" class="deleteButton">Delete</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
	<div id="status" class="status"></div>
</div>
<script src="scripts/main2.js"></script>
<?php
include 'footer.php';
?>