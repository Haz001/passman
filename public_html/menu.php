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
<button id="refresh">Refresh</button>
<div id="list"></div>
<div id="passwords"></div>
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
			success: function(response){
				while (div.children().length > 0)// child deleter
					div.children()[0].remove();
				for (let i = 0; i < response.length; i++) {
					let tmp = document.createElement("button");
					tmp.innerText = response[i]["website_name"];
					tmp.name = response[i]["web_address"];
					tmp.value = response[i]["website_id"];
					tmp.addEventListener('click',function(){
						grabPasswords(tmp);
					});
					div.append(tmp);
				}
			}
		});
	}
	function grabPasswords(btn) {
		let div = $("#passwords");
		console.log(btn);
		$.ajax({
			type: "GET",
			url: "/scripts/ajax.php",
			data: "get=passwords&website_id="+btn.value,
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
			success: function(response){
				div.empty();
				for (let i = 0; i < response.length; i++) {
					let tmp = document.createElement("input");
					tmp.type = "text";
					tmp.name = 	response[i]["password_id"];
					tmp.value = response[i]["username"];
					let tmp2 = document.createElement("input");
					tmp2.type = "text";
					tmp2.name =  response[i]["password_id"];
					tmp2.value = response[i]["password"];
					console.log(tmp);
					div.append(tmp);
					div.append(tmp2);
				}
			}
		});
	}
	$("#refresh").click(function(e) {
		//e.preventDefault();
		listWebsites($("#list"));
	});
</script>
<?php require "footer.php"; ?>