$(document).ready(function () {
	$.ajax({
		type: "POST",
		url: "userInfo.php",
		dataType: "json",
		success: function (response) {
			setName(response);
		},
	});
});

function setName(info) {
	$("#name").html("Hello " + info["first_name"]);
}
