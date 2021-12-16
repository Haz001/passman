$(document).ready(function () {
	getDetails();
	$("#submit").click(function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "scripts/manageAccount.php",
			data: $("#user_details").serialize() + "&request=update",
			dataType: "json",
			success: function (response) {
				console.log(response);
				if (response["result"] == "success") {
					$("#output").html("Account Details Updated Successfully");
				} else {
					$("#output").html("There was an error");
				}
				refresh();
			},
		});
	});
	$("#delete").click(function (e) {
		e.preventDefault();
		if (
			confirm(
				"This will delete your entire account permanently, are you sure?"
			)
		) {
			$.ajax({
				type: "POST",
				url: "scripts/manageAccount.php",
				data: { request: "delete" },
				dataType: "json",
				success: function (response) {
					if (response["result"] == "success") {
						window.location.replace("logout.php");
					} else {
						$("#output").html(
							"There was an error, try again later"
						);
					}
				},
			});
		}
	});
	$("#switch").click(function (e) {
		e.preventDefault();
		$("#user_details").hide();
		$("#passform").show();
	});
	$("#submitPass").click(function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "scripts/manageAccount.php",
			data: $("#passform").serialize() + "&request=changePassword",
			dataType: "json",
			success: function (response) {
				console.log(response);
				if (response["result"] != "errpr") {
					$("#output").html("Password updated succesfully");
				} else {
					$("#output").html("There was an error");
				}
				refresh();
			},
		});
	});
});
function getDetails() {
	$.ajax({
		// Sends an async POST request to the userData script and returns an array of user details and address
		type: "POST",
		url: "scripts/manageAccount.php",
		data: { request: "all" },
		dataType: "json",
		cache: false,
		statusCode: {
			404: function () {
				console.log("error 404, no data returned from sql query");
				window.location.replace("/logout.php");
			},
			403: function () {
				console.log("error 403, access denied");
				window.location.replace("/login.php");
			},
		},
		success: function (response) {
			for (const [key, value] of Object.entries(response)) {
				$("[name='" + key + "']").val(value);
			}
		},
	});
}

function refresh() {
	getDetails();
}
