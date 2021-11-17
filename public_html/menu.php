<?php require_once "header.php";
//
?>
<style>
	button::after {
		content: attr(name);
		display: block;
		color: gray;
	}
</style>
<div id="list"><button id="refresh">Refresh</button> </div>
<script>
	function listWebsites(div) {
		$.ajax({
			type: "GET",
			url: "/scripts/ajax.php",
			data: "get=websites",
			dataType: "JSON",
			statusCode: {
				444: function() {
					alert("cant find data");
				},
				500: function() {
					alert("PHP code error");
				},
				401: function() {
					alert("please authenticate");
					document.location = "/logout.php"
				}
			},
			success: function(response) {
				console.log(response);
				for (let i = 0; i < response.length; i++) {
					let tmp = document.createElement("button");
					tmp.innerText = response[i]["website_name"];
					tmp.name = response[i]["web_address"];
					tmp.value = response[i]["website_id"];
					div.append(tmp);
				}
			}
		});
	}
	$("#refresh").click(function(e) {
		e.preventDefault();
		listWebsites($("#list"));

	});
</script>
<?php require "footer.php"; ?>