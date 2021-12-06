$(document).ready(function () {
	var parts = window.location.search.substr(1).split("&");
	var $_GET = {};
	for (var i = 0; i < parts.length; i++) {
		var temp = parts[i].split("=");
		$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
	}
	errorMsg($_GET);
	$("[class='section2']").hover(
		function () {
			$("[class='accountDrop']").show();
			$("[class='accountDrop']").css("display", "flex");
		},
		function () {
			$("[class='accountDrop']").hide();
		}
	);
});

function errorMsg($_GET) {
	switch ($_GET["error"]) {
		case "ef":
			displayErrorMsg(
				"There are empty fields, please check your details and try again"
			);
			break;
		case "notfound":
			$("input").css("border-color", "red");
			displayErrorMsg(
				"Username/Password inccorect, please check your details and try again"
			);
			break;
		case "stmtfailed":
			displayErrorMsg(
				"Database error, please try again in a few minutes, if issue persists, contact support"
			);
			break;
		case "otpExpired":
			displayErrorMsg(
				"Your one time passcode has expired, please login again, otp's expire after 20 minutes"
			);
			break;
		case "maxlength":
			displayErrorMsg("Max input length exceeded");
			break;
		case "complexity":
			displayErrorMsg(
				'Password does not meet complexity requirements <br> <ul style="list-style-type: none"><li>8 Characters</li><li>1 Special Character</li><li>1 Uppercase Character</li><li>1 Number</li><li>Not be in top 100,000 most common passwords</ul>'
			);
			$("[name='password']").css("border-color", "red");
			break;
		case "otpIncorrect":
			displayErrorMsg("One time passcode is incorrect, please try again");
			break;
		case "ue":
			displayErrorMsg(
				"A user with with your username or email already exists, please try <a href='index.php'>logging in</a>"
			);
			break;
		case "commonPassword":
			displayErrorMsg(
				"Your password has been found in a common list of passwords, please try a more complex password"
			);
			break;
		default:
			break;
	}
}

function displayErrorMsg(message) {
	$("#error").css("background", "lightcoral");
	$("#error").css("padding", "10px");
	$("#error").html(message);
}
