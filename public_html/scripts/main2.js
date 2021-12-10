/**
 * This is the on ready function
 */
$(function () {

});

/**
 * This function will grab the website list
 * @param {object} div - the div that it uses
 */
function listWebsites(div) {
	$.getJSON("/scripts/ajax.php", {
		get:"websites"
	}, //setups jquery get to get website list
		function (data, textstatus, jqxhr) {
			if(textstatus == "success"){ // checks if success
				$("#list").empty();
				for (let i = 0; i < response.length; i++) {
					let websiteButton = document.createElement("button");
					websiteButton.innerText = response[i]["website_name"];
					websiteButton.name = response[i]["web_address"];
					websiteButton.classList.add("websiteName");
					div.append(websiteButton);
				}
			}
		}
	);
};

listWebsites();