$(document).ready(function () {
	$.ajax({
		type: "POST",
		url: "scripts/userInfo.php",
		dataType: "json",
		success: function (response) {
			setName(response);
		},
	});
});

function setName(info) {
	$("#name").html("Hello " + info["first_name"]);
}
