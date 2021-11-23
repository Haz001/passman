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

<body class="contactPage">
    <div class="contactTitle">
        <h1>We're here</h1>
        <p>Feel free to ask a question</p>
    </div>

    <div class="contactMain">
        <div class="contactForm">
            <form action="" method="GET">
                <div class="contactRow1">
                    <label for="fName">First Name</label>
                    <input type="text" id="fName" name="fName">
                    <label for="lName">Last Name</label>
                    <input type="text" id="lName" name="lName">
                </div>
                <br>
                <div class="contactRow2">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                    <label for="confirmEmail">Confirm Email</label>
                    <input type="email" id="confirmEmail" name="confirmEmail">
                </div>
                <br>
                <div class="contactRow3">
                    <label for="message">Message:</label>
                    <input type="text" id="message" name="message">
                    <input type="submit" value="Send" class="contactSend">
                </div>
            </form>
        </div>
        <div class="contactTeam">
            <h2 class="contactTeamTitle">Customer Service Team:</h2>
            <div class="contactTeamMemeber1">
                <img src="img/profilePicture.jpeg" alt="profilePic">
                <h5 class="meetName">Meet Harry</h5>
                <p>About Harry: some text</p>
            </div> <hr>
            <div class="contactTeamMemeber2">
                <img src="img/profilePicture.jpeg" alt="profilePic">
                <h5 class="meetName">Meet Ben</h5>
                <p>Meet Ben: some text</p>
            </div> <hr>
            <div class="contactTeamMemeber3">
                <img src="img/profilePicture.jpeg" alt="profilePic">
                <h5 class="meetName">Meet Oskar</h5>
                <p>Meet Ben: some text</p>
            </div>
        </div>
    </div>
</body>
</html>

