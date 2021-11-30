var count = 0;
function listWebsites(div) {
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: "get=websites",
		dataType: "JSON",
		statusCode: {
			444: function () {
				alert("cant find data");
			},
			500: function () {
				alert("PHP code error");
			},
			401: function () {
				alert("please authenticate");
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			while (div.children().length > 0) div.children()[0].remove();
			for (let i = 0; i < response.length; i++) {
				let tmp = document.createElement("button");
				tmp.innerText = response[i]["website_name"];
				tmp.name = response[i]["web_address"];
				tmp.value = response[i]["website_id"];
				tmp.addEventListener("click",grabPasswords);
				div.append(tmp);
			}
			let tmp = document.createElement("newWebsite");
			tmp.innerText = "Add Website";
			tmp.name = "newWebsiteBtn";
			tmp.addEventListener("click",addWebsite);
		},
	});
}
function addWebsite(evt) { 
	let overlay = document.createElement("div");
	overlay.class = "overlay";
 }
function editPasswords(evt) {
	let btn = evt.currentTarget;
	let tmp = $('[name="' + btn.name + '"]');
	for (let i = 0; i < tmp.length; i++) {
		let tPart = tmp[i].id.split(":");
		let part = "";
		if (tPart.length == 3)
			part = tPart[2];
		if (part == "un" || part == "pw")
			tmp[i].disabled = false;
	}
	btn.value = "Save";
	btn.removeEventListener("click", editPasswords);
	btn.addEventListener("click", updatePasswords);
}
function updatePasswords(evt) {
	let newUsername = "";
	let newPassword = "";
	let btn = evt.currentTarget;
	let tmp = $('[name="' + btn.name + '"]');
	for (let i = 0; i < tmp.length; i++) {
		let tPart = tmp[i].id.split(":");
		let part = "";
		if (tPart.length == 3)
			part = tPart[2];
		if (part == "un")
			newUsername = tmp[i].value;
		if (part == "pw")
			newPassword = tmp[i].value;
	}
	let tPart = btn.id.split(":");
	let part = "";
	if (tPart.length > 2)
		part = tPart[1];
	let passwordId = btn.name.split(":")[1];
	$.ajax({
		type: "POST",
		url: "/scripts/ajax.php",
		data: {
			update: "password",
			password_id: passwordId,
			username: newUsername,
			password: newPassword,
		},
		dataType: "JSON",
		statusCode: {
			444: function () {
				alert("cant find data");
			},
			500: function () {
				alert("PHP code error");
			},
			401: function () {
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			alert("Password updated")
			btn.removeEventListener("click",updatePasswords);
			btn.addEventListener("click",editPasswords);
			let tmp = $('[name="'+btn.name+'"]');
			for (let i = 0;i < tmp.length;i++)
			{
				let tPart = tmp[i].id.split(":");
				let part = "";
				if(tPart.length == 3)
					part = tPart[2];
				if((part == "un")||(part == "pw"))
					tmp[i].disabled = false;
			}
		}
	});
	btn.removeEventListener("click", updatePasswords);
}
function grabPasswords(evt) {
	let btn = evt.currentTarget;
	let div = $("#passwords");
	console.log(btn);
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: "get=passwords&website_id=" + btn.value,
		dataType: "JSON",
		statusCode: {
			444: function () {
				alert("cant find data");
			},
			500: function () {
				alert("PHP code error");
			},
			401: function () {
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			div.empty();
			let tmp0 = document.createElement("h1");
			tmp0.innerText = btn.innerText;
			tmp0.name = btn.name;
			tmp0.id = count;
			count++;
			let tmp1 = document.createElement("h2");
			tmp1.innerText = btn.name;
			div.append(tmp0);
			div.append(tmp1);
			for (let i = 0; i < response.length; i++) {
				tmpName = "password_id:" + response[i]["password_id"];
				if (i > 0) {
					let hr = document.createElement("hr");
					div.append(hr);
				}
				let br = document.createElement("br");
				let tmp0 = document.createElement("h1");
				tmp0.innerText = "Account " + i.toString() + ":";
				let tmp1 = document.createElement("input");
				tmp1.type = "text";
				tmp1.disabled = true;
				tmp1.id = tmpName + ":un";
				tmp1.name = tmpName;
				tmp1.value = response[i]["username"];
				let tmp2 = document.createElement("input");
				tmp2.type = "text";
				tmp2.disabled = true;
				tmp2.id = tmpName + ":pw";
				tmp2.name = tmpName;
				tmp2.value = response[i]["password"];
				let tmp3 = document.createElement("input");
				tmp3.type = "button";
				tmp3.id = tmpName + ":btn";
				tmp3.name = tmpName;
				tmp3.value = "Edit";
				tmp3.addEventListener("click", editPasswords);
				div.append(tmp0);
				div.append(tmp1);
				div.append(br);
				div.append(tmp2);
				div.append(br);
				div.append(tmp3);
			}
		},
	});
}
$(document).ready(function () {
	listWebsites($("#list"));
	$("#refresh").click(function (e) {
		e.preventDefault();
		listWebsites($("#list"));
	});
});
