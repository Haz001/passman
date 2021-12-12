"use strict";//forces strict js

// @type {array}
var websites = [];
var passwords = [];
var currentWebsite = {"website_id":0};
var currentPasswordId = 0;
/**
 * This is the on ready function
 */
$(function () {
	displayWebsites();
	$("#mainAddImport").on("click", function () {
		addWebsite();
	});	
	$("#passwordEdit").on("click", function () {
		editPasswords();
	});	
	$("#passwordDelete").on("click", function () {
		deletePasswords();
	});	
});
function statusUpdate(title,status){
	let titleTextNode = document.createTextNode("Function: "+title);
	let statusTextNode = document.createTextNode("Stauts: "+status);
	$("#status").append(titleTextNode,statusTextNode);
}
/**
 * This function will grab the website list
 * @param {function} callFunc - This is a function called once function has been updated
 */
function grabWebsites(callFunc) {
	//$("#status").text("Requesting Websites");// tells user it is requesting website
	statusUpdate("grabWebsite","Requesting Websites")
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: {
			get:"websites"
		},
		dataType: "JSON",
		success: function (data,textstatus,jqxhr) {
			if(textstatus == "success"){ // checks if success
				$("#status").text("Success: Websites");// tells user it is requesting website
				websites = data;//sets global variable to list websites
				if(typeof callFunc == "function"){
					callFunc();
				}
			}
		}
	});
}
/**
 * This function genorates and displays buttons of websits in #list
 * @param {boolean} forceUpdate - Forces an update of website list
 */
function displayWebsites(forceUpdate) {
	if((websites.length == 0)||((typeof forceUpdate == "boolean")&&(forceUpdate))){
		grabWebsites(displayWebsites);
	}else{
		let w = websites;
		let list = $("#list");
		list.empty();
		for (let i = 0; i < w.length;i++){
			let websiteBtn = document.createElement("button");
			websiteBtn.addEventListener("click",function (){
				currentWebsite = w[i];
				displayAccounts(w[i]["website_id"],true);
			});
			websiteBtn.innerText = w[i]["website_name"];// grabs website name
			websiteBtn.name = w[i]["web_address"];// grabs website address, css displays this under name
			list.append(websiteBtn);
			if(((i == 0)&&(currentWebsite["website_id"] == 0))||(currentWebsite["website_id"] == w[i]["website_id"])){
				websiteBtn.click();
			}
		}
	}
}
function grabPasswords(websiteId,callFunc){
	statusUpdate("grabPasswords","Requesting Password")
	$.ajax({
		type: "GET",
		url: "/scripts/ajax.php",
		data: {
			get:"passwords",
			website_id:websiteId
		},
		dataType: "JSON",
		success: function (data,textstatus,jqxhr) {
			if(textstatus == "success"){ // checks if success
				data = data.sort((a,b)=> {
					var a1 = a["username"].toLowerCase();
					var b1 = b["username"].toLowerCase();
					return a1<b1 ?-1:a1> b1? 1 :0;
				});
				$("#status").text("Success: Passwords");// tells user it is requesting website
				passwords = data;//sets global variable to list websites
				if(typeof callFunc == "function"){
					callFunc();
				}
			}
		}
	});
}

function displayAccounts(websiteId,forceUpdate) {
	editPasswords(true);//resets edit button
	$("#passwordUsername").val("");
	$("#passwordPassword").val("");
	let list = $("#mainUserButtons");
	currentPasswordId = 0;
	$("#mainWebsiteTitle").text(currentWebsite["website_name"]);
	list.empty();
	if(((typeof forceUpdate == "boolean")&&(forceUpdate))){
		grabPasswords(websiteId,displayAccounts);
	}else{
		let p = passwords;
		for (let i = 0; i < p.length;i++){
			let accountbtn = document.createElement("button");
			accountbtn.addEventListener("click",function (){
				currentPasswordId = p[i]["password_id"];
				displayPassword(p[i]["password_id"]);
			});
			accountbtn.innerText = p[i]["username"];// grabs website name
			list.append(accountbtn);
			if(((i == 0)&&((typeof currentPasswordId == "number")&&(currentPasswordId == 0)))||(currentPasswordId == p[i]["password_id"])){
				accountbtn.click();
			}
		}
	}
}
/**
 * This function genorates and displays buttons of websits in #list
 * @param {boolean} forceUpdate - Forces an update of website list
 */
function displayPassword(passwordId) {
		
	let p = passwords;

	editPasswords(true);//resets edit button
	for (let i = 0; i < p.length;i++){
		if(p[i]["password_id"] == passwordId){
			$("#passwordUsername").val( p[i]["username"]);
			$("#passwordPassword").val( p[i]["password"]);
		}
	}
}
//done, tested
function editPasswords(reset = false)
{
	if(reset){
			$("#passwordUsername").attr("disabled","true");
			$("#passwordPassword").attr("disabled","true");
			$("#passwordEdit").text("Edit");
	}else if(currentPasswordId != 0)	{
		if($("#passwordEdit").text().toLowerCase() == "edit"){
			$("#passwordUsername").removeAttr("disabled");
			$("#passwordPassword").removeAttr("disabled");
			$("#passwordEdit").text("Save");
		}
		else if($("#passwordEdit").text().toLowerCase() == "save"){
			$.ajax({
				type: "POST",
				url: "/scripts/ajax.php",
				data: {
					update: "password",
					password_id: currentPasswordId,
					username: $("#passwordUsername").val(),
					password: $("#passwordPassword").val()
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
					if(response["success"] == 1){
						$("#passwordUsername").attr("disabled","true");
						$("#passwordPassword").attr("disabled","true");
						$("#passwordEdit").text("Edit");
					}
				},
			});
		}else{
			$("#passwordUsername").attr("disabled","true");
			$("#passwordPassword").attr("disabled","true");
			$("#passwordEdit").text("Edit");
		}
	}
}
function addWebsite(){
	$("#passwordEdit").on("click", function () {
		editPasswords();
	});	
}
function addPasswords(step2 = false,website_id = null,username = 0,password = 0){
	if(step2 == false){
		if (document.getElementById("mkPwOverlay") == null) {
			if(website_id == null){
				website_id = currentWebsite["website_id"];
			}
			let overlay = document.createElement("div");
			overlay.classList.add("overlay");
			overlay.id = "mkPwOverlay";
			let span = document.createElement("span");
			span.innerText = "Add a new password:";
			let pwUsername = document.createElement("input");
			pwUsername.type = "text";
			pwUsername.placeholder = "Username";
			pwUsername.name = "pwUsername";
			pwUsername.value = username;
			pwUsername.id = overlay.id+">username";
			let pwPassword = document.createElement("input");
			pwPassword.type = "password";
			pwPassword.placeholder = "Password";
			pwPassword.name = "pwPassword";
			pwUsername.id = overlay.id+">password";
			let wbAddBtn = document.createElement("input");
			wbAddBtn.type = "button";
			wbAddBtn.value = "Add Password";
			wbAddBtn.addEventListener("click", function (){
				addPasswords(true,website_id,pwUsername.value,pwPassword.value)
			});
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

			overlay.appendChild(pwUsername);
			overlay.appendChild(pwPassword);

			overlay.appendChild(btnHolder);
			document.body.appendChild(overlay);
		}
	}
	else{
	$.post(
		"/scripts/ajax.php",
		{
			add: "password",
			website_id: website_id,
			username: username,
			password: password
		},
		function (data, textStatus, jqXHR) {
			if (data["result"] == 1)
				document.getElementById("mkPwOverlay").remove();
		},
		"json"
	);
	}
}
//done, notTested
function deletePasswords(){
	$.ajax({
		type: "POST",
		url: "/scripts/ajax.php",
		data: {
			delete: "password",
			password_id: currentPasswordId
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
				displayWebsites(true);
				$("#passwordUsername").removeAttr("disabled");
				$("#passwordPassword").removeAttr("disabled");
			}

		}
	});
}