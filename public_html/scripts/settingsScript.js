$(function () {
	$("#submitsettings").click(function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "scripts/manageAccount.php",
			data: $("#settingsform").serialize() + "&request=updateSettings",
			dataType: "JSON",
			success: function (response) {
				$("#output").html("Settings have been updated successfuly");
				refresh();
			},
		});
	});
	refresh();
});

function refresh() {
	$.ajax({
		type: "POST",
		url: "scripts/manageAccount.php",
		data: { request: "getSettings" },
		dataType: "json",
		success: function (response) {
			for (const [key, value] of Object.entries(response)) {
				$("[name='" + key + "']").val(value);
			}
		},
	});
}
