<?php include 'header.php';?>

<div class="mainPage" id="contactMainPage">
    <div class="contactTitle">
        <h1>We're here</h1>
        <p>Feel free to ask a question</p>
    </div>
    <div class="mainBody">
        <div class="mainSection1" id="addMainSection1">
            <form action="" method="GET">
                        <label class="contactFName" for="fName">First Name</label>
                        <input type="text" id="fName" name="fName" required>
                        <label for="lName">Last Name</label>
                        <input type="text" id="lName" name="lName" required>               
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                        <label for="confirmEmail">Confirm Email</label>
                        <input type="email" id="confirmEmail" name="confirmEmail" required>
                        <label for="message">Message:</label>
                        <input type="text" id="message" name="message" required>
                        <input type="submit" value="Send" class="contactSend">
                </form>
        </div>
        <div class="verticalLine">
        </div>
        <div class="mainSection2" id="contactMainSection2">
            <h2 class="contactTeamTitle">Customer Service Team:</h2>
            <div class="contactTeamMembers">
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
    </div>
</div>

<?php 
include 'footer.php';
?>



