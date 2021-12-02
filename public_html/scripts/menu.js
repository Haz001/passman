var count = 0;
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
	if( document.getElementById("mkWbOverlay") == null){
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
		wbAddr.name ="wbAddr";
		let wbAddBtn = document.createElement("input");
		wbAddBtn.name = "wbAddBtn";
		wbAddBtn.type = "button";
		wbAddBtn.value = "Add Website";
		wbAddBtn.addEventListener("click",createWebsite);
		let wbCancBtn = document.createElement("input");
		wbCancBtn.type = "button";
		wbCancBtn.value = "Cancel";
		wbCancBtn.name = "wbCancBtn";
		wbCancBtn.addEventListener("click",function (){
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
function addPassword(evt) { 
	let btn = evt.currentTarget;
	let wbId = btn.name;
	console.log(wbId);
	if( document.getElementById("mkPwOverlay") == null){
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
		wbAddr.name ="pwPassword";
		let wbAddBtn = document.createElement("input");
		wbAddBtn.name = wbId;
		wbAddBtn.type = "button";
		wbAddBtn.value = "Add Password";
		wbAddBtn.addEventListener("click",createPassword);
		let wbCancBtn = document.createElement("input");
		wbCancBtn.type = "button";
		wbCancBtn.value = "Cancel";
		wbCancBtn.name = "pwCancBtn";
		wbCancBtn.addEventListener("click",function (){
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
function createWebsite(evt){
	let wbName = "";
	let wbAddr = "";

	let overlayChildren =  evt.currentTarget.parentNode.parentNode.children;
	for(let i = 0; i < overlayChildren.length;i++){
		let child = overlayChildren[i];
		if(child.name == "wbAddr"){
			wbAddr = child.value;
		}else if(child.name == "wbName"){
			wbName = child.value;
		}
	}
	$.post("/scripts/ajax.php", data={
		add:"website",
		website_name:wbName,
		website_address:wbAddr
	},
		function (data, textStatus, jqXHR) {
			if(data == 1)
				document.getElementById("mkWbOverlay").remove();
		},
		"json"
	);
}
function createPassword(evt){
	let btn = evt.currentTarget;
	let wbId = btn.name;
	let pwUsername = "";
	let pwPassword = "";

	let overlayChildren =  evt.currentTarget.parentNode.parentNode.children;
	for(let i = 0; i < overlayChildren.length;i++){
		let child = overlayChildren[i];
		if(child.name == "pwUsername"){
			pwUsername = child.value;
		}else if(child.name == "pwPassword"){
			pwPassword = child.value;
		}
	}
	$.post("/scripts/ajax.php", data={
		add:"password",
		website_id:wbId,
		username:pwUsername,
		password:pwPassword,

	},
		function (data, textStatus, jqXHR) {
			if(data == 1)
				document.getElementById("mkPwOverlay").remove();
		},
		"json"
	);
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
			console.log("Password updated")
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
			let tmp4 = document.createElement("input");
			tmp4.type = "button";
			tmp4.id = btn.value + ":btn";
			tmp4.name = btn.value;
			tmp4.value = "add";
			tmp4.addEventListener("click", addPassword);
			div.append(tmp4);
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
