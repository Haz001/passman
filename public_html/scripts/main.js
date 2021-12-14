var count = 0;
document.getElementById("mainAddImport").addEventListener("click",addWebsite);
function listWebsites(div) {
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: "get=websites",
		dataType: "JSON",
		statusCode: {
			444: function () {
				console.log("cant find data");
			},
			500: function () {
				console.log("PHP code error");
			},
			401: function () {
				console.log("please authenticate");
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			while (div.children().length > 0) div.children()[0].remove();
			//$("#passwords").empty();
			for (let i = 0; i < response.length; i++) {
				let websiteButton = document.createElement("button");
				websiteButton.innerText = response[i]["website_name"];
				websiteButton.name = response[i]["web_address"];
				websiteButton.value = response[i]["website_id"];
				websiteButton.classList.add("websiteName");
				websiteButton.addEventListener("click",grabPasswords);
				div.append(websiteButton);
			}
		},
	});
}
function addWebsite(evt) {
	if (document.getElementById("mkWbOverlay") == null) {
		let overlay = document.createElement("div");
		overlay.classList.add("overlay");
		overlay.id = "mkWbOverlay";
		let span = document.createElement("span");
		span.innerText = "Add a new website to your account:";
		let wbName = document.createElement("input");
		wbName.type = "text";
		wbName.placeholder = "Website Name: Google";
		wbName.name = "wbName";
		let wbAddr = document.createElement("input");
		wbAddr.type = "text";
		wbAddr.placeholder = "Website Address: http://example.com";
		wbAddr.name = "wbAddr";
		let wbAddBtn = document.createElement("input");
		wbAddBtn.name = "wbAddBtn";
		wbAddBtn.type = "button";
		wbAddBtn.value = "Add Website";
		wbAddBtn.addEventListener("click", createWebsite);
		let wbCancBtn = document.createElement("input");
		wbCancBtn.type = "button";
		wbCancBtn.value = "Cancel";
		wbCancBtn.name = "wbCancBtn";
		wbCancBtn.addEventListener("click", function () {
			document.getElementById("mkWbOverlay").remove();
		});
		let btnHolder = document.createElement("div");
		btnHolder.classList.add("btn");
		btnHolder.appendChild(wbCancBtn);
		btnHolder.appendChild(wbAddBtn);
		overlay.appendChild(span);

		overlay.appendChild(wbName);
		overlay.appendChild(wbAddr);

		overlay.appendChild(btnHolder);
		document.body.appendChild(overlay);
	}
}
function refreshPassword(evt) { 
	let btn = evt.currentTarget;
	let wbId = btn.name;
	let allWebsiteButtons = $(".websiteName");
	for (let i = 0;i < allWebsiteButtons.length;i++){
		if(allWebsiteButtons[i].value == wbId){
			allWebsiteButtons[i].click();
		}
	}
}
function addPassword(evt) { 
	let btn = evt.currentTarget;
	let wbId = btn.name;
	console.log(wbId);
	if (document.getElementById("mkPwOverlay") == null) {
		let overlay = document.createElement("div");
		overlay.classList.add("overlay");
		overlay.id = "mkPwOverlay";
		let span = document.createElement("span");
		span.innerText = "Add a new password:";
		let wbName = document.createElement("input");
		wbName.type = "text";
		wbName.placeholder = "Username";
		wbName.name = "pwUsername";
		let wbAddr = document.createElement("input");
		wbAddr.type = "password";
		wbAddr.placeholder = "Password";
		wbAddr.name = "pwPassword";
		let wbAddBtn = document.createElement("input");
		wbAddBtn.name = wbId;
		wbAddBtn.type = "button";
		wbAddBtn.value = "Add Password";
		wbAddBtn.addEventListener("click", createPassword);
		let wbCancBtn = document.createElement("input");
		wbCancBtn.type = "button";
		wbCancBtn.value = "Cancel";
		wbCancBtn.name = "pwCancBtn";
		wbCancBtn.addEventListener("click", function () {
			document.getElementById("mkPwOverlay").remove();
		});
		let btnHolder = document.createElement("div");
		btnHolder.classList.add("btn");
		btnHolder.appendChild(wbCancBtn);
		btnHolder.appendChild(wbAddBtn);
		overlay.appendChild(span);

		overlay.appendChild(wbName);
		overlay.appendChild(wbAddr);

		overlay.appendChild(btnHolder);
		document.body.appendChild(overlay);
	}
}
function createWebsite(evt) {
	let wbName = "";
	let wbAddr = "";

	let overlayChildren = evt.currentTarget.parentNode.parentNode.children;
	for (let i = 0; i < overlayChildren.length; i++) {
		let child = overlayChildren[i];
		if (child.name == "wbAddr") {
			wbAddr = child.value;
		} else if (child.name == "wbName") {
			wbName = child.value;
		}
	}
	$.post(
		"/scripts/ajax.php",
		(data = {
			add: "website",
			website_name: wbName,
			website_address: wbAddr,
		}),
		function (data, textStatus, jqXHR) {
			if (data == 1) document.getElementById("mkWbOverlay").remove();
		},
		"json"
	);
}
function createPassword(evt) {
	let btn = evt.currentTarget;
	let wbId = btn.name;
	let pwUsername = "";
	let pwPassword = "";

	let overlayChildren = evt.currentTarget.parentNode.parentNode.children;
	for (let i = 0; i < overlayChildren.length; i++) {
		let child = overlayChildren[i];
		if (child.name == "pwUsername") {
			pwUsername = child.value;
		} else if (child.name == "pwPassword") {
			pwPassword = child.value;
		}
	}
	$.post(
		"/scripts/ajax.php",
		(data = {
			add: "password",
			website_id: wbId,
			username: pwUsername,
			password: pwPassword,
		}),
		function (data, textStatus, jqXHR) {
			if (data["result"] == 1)
				document.getElementById("mkPwOverlay").remove();
		},
		"json"
	);
}
function editPasswords(evt) {
	let btn = evt.currentTarget;
	let tmp = $('[name="' + btn.name + '"]');
	for (let i = 0; i < tmp.length; i++) {
		if (tmp[i].id == "passwordUsername" || tmp[i].id == "passwordPassword")
			tmp[i].disabled = false;
	}
	btn.value = "Save";
	btn.removeEventListener("click", editPasswords);
	btn.addEventListener("click", updatePasswords);
}
function deletePasswords(evt) {
	let btn = evt.currentTarget;
	let tPart = btn.id.split(":");
	let part = "";
	if (tPart.length > 2)
		part = tPart[1];
	let passwordId = btn.name.split(":")[1];
	$.ajax({
		type: "POST",
		url: "/scripts/ajax.php",
		data: {
			delete: "password",
			password_id: passwordId
		},
		dataType: "JSON",
		statusCode: {
			444: function () {
				console.log("cant find data");
			},
			500: function () {
				console.log("PHP code error");
			},
			401: function () {
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			if(response["success"] == 1)
			{
				console.log("deleted");
				$("#refreshBtn").click();
			}

		}
	});
	btn.removeEventListener("click", updatePasswords);
}
function updatePasswords(evt) {
	let newUsername = document.getElementById("passwordUsername").value;
	let newPassword = document.getElementById("passwordPassword").value;
	
	let btn = evt.currentTarget;
	let tmp = $('[name="' + btn.name + '"]');
	// for (let i = 0; i < tmp.length; i++) {
	// 	if (tmp[i].id = "passwordUsername")
	// 		newUsername = tmp[i].value;
	// 	if (tmp[i].id = "passwordPassword")
	// 		newPassword = tmp[i].value;
	// }
	let tPart = btn.id.split(":");
	let part = "";
	if (tPart.length > 2) part = tPart[1];
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
				console.log("cant find data");
			},
			500: function () {
				console.log("PHP code error");
			},
			401: function () {
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			console.log("Password updated");
			btn.removeEventListener("click", updatePasswords);
			btn.addEventListener("click", editPasswords);
			let tmp = $('[name="' + btn.name + '"]');
			for (let i = 0; i < tmp.length; i++) {
				let tPart = tmp[i].id.split(":");
				let part = "";
				if (tPart.length == 3) part = tPart[2];
				if (part == "un" || part == "pw") tmp[i].disabled = false;
			}
		},
	});
	btn.removeEventListener("click", updatePasswords);
}
function grabPasswords(evt) {
	let btn = evt.currentTarget;
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: "get=passwords&website_id=" + btn.value,
		dataType: "JSON",
		statusCode: {
			444: function () {
				console.log("cant find data");
			},
			500: function () {
				console.log("PHP code error");
			},
			401: function () {
				document.location = "/logout.php";
			},
		},
		success: function (response) {
			document.getElementById("mainWebsiteTitle").innerText = btn.innerText;
			document.getElementById("mainWebsiteTitle").name = btn.name;
			document.getElementById("mainWebsiteLink").href = btn.name;
			let mainUserButtons = $("#mainUserButtons");
			mainUserButtons.empty();

			let refBtn = document.createElement("button");
			refBtn.id = "refreshBtn";
			refBtn.name = btn.value;
			refBtn.classList.add("name");
			refBtn.innerText = "Refresh";
			refBtn.addEventListener("click", refreshPassword);
			mainUserButtons.append(refBtn);
			if(typeof response !== 'undefined'){
				document.getElementById("passwordUsername").value = "";
				document.getElementById("passwordUsername").name = "password_id:";
				document.getElementById("passwordPassword").value = "";
				document.getElementById("passwordPassword").name = "password_id:";
				for (let i = 0; i < response.length; i++) {
					let tmpButton = document.createElement("button");
					tmpName = "password_id:" + response[i]["password_id"];
					tmpButton.id = tmpName;
					tmpButton.innerText = response[i]["username"];
					tmpButton.addEventListener("click",function (){

						document.getElementById("passwordUsername").value = response[i]["username"];
						document.getElementById("passwordUsername").name = tmpName;
						document.getElementById("passwordPassword").value = response[i]["password"];
						document.getElementById("passwordPassword").name = tmpName;
						let editBtn = document.getElementById("passwordEdit");
						let deleteBtn = document.getElementById("passwordDelete");
						editBtn.value = response[i]["password_id"];
						deleteBtn.value = response[i]["password_id"];
                        editBtn.name = tmpName;
                        deleteBtn.name = tmpName;
					 	editBtn.addEventListener("click", editPasswords);
					 	deleteBtn.addEventListener("click", deletePasswords);
					});
					mainUserButtons.append(tmpButton);
				}
			} else {
				console.log("No data");
			}
			let addBtn = document.createElement("button");
			addBtn.id = btn.value + ":btn";
			addBtn.name = btn.value;
			addBtn.classList.add("name");
			addBtn.innerText = "Add";
			addBtn.addEventListener("click", addPassword);
			mainUserButtons.append(addBtn);
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
