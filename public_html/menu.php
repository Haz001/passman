<?php require_once "header.php";
//?>
    <div id="list"><button onclick="listWebsites(this.parent)" >Refresh</button> </div>
<script>
    function listWebsites(result){
        if(result.status == 200) {
            let websitesJson = result.responseText;
            let websites = JSON.parse(websitesJson);
            console.log(websites);
            for (let i = 0;i < websites.length;i++){
                let tmp = document.createElement("button");
                tmp.innerText = websites[i]["website_name"]
                tmp.name = websites[i]["web_address"];
                tmp.value = websites[i]["website_id"];
                console.log(tmp);
            }
        }else{
            alert("Error")
        }
    }
    function something() {
        var getWebsitesAjax = jQuery.ajax(url:"/scripts/ajax.php?get=websites", success: listWebsites() );
    }

    something();
</script>
<?php require "footer.php"; ?>