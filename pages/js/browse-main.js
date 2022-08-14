const loadingText = ["Searching...", "Loading...", "Foraging...", "Processing...","Examining...","Finding..."];

function submit_search() {
    document.getElementById("district_result_container").style.visibility = "hidden";
    document.getElementById("street_units_result_container").style.visibility = "hidden";
    document.getElementById("streets_result_container").style.visibility = "hidden";
    document.getElementById("building_result_container").style.visibility = "hidden";

    var randomNum = Math.floor(Math.random() * 6);
    document.getElementById("loading_text").innerHTML = loadingText[randomNum];
    if (document.getElementById("search_all_checkbox").checked) {
        var search_categories = "districts,street_units,streets,buildings";
    }
    else {
        var search_categories = ",";
        if (document.getElementById("search_district_checkbox").checked) {
            search_categories = search_categories + "districts";
        }
        if (document.getElementById("search_street_unit_checkbox").checked) {
            search_categories = search_categories + ",street_units";
        }
        if (document.getElementById("search_streets_checkbox").checked) {
            search_categories = search_categories + ",streets";
        }
        if (document.getElementById("search_building_checkbox").checked) {
            search_categories = search_categories + ",buildings";
        }
    }
    if (search_categories != ",") {
        var search_term = document.getElementById("search_input_box").value;
        if (search_term.length > 0) {
            $.ajax({
                url: "/pages/search/search_handle.php",
                type: "POST",
                data: {
                    search_term:search_term,
                    search_categories:search_categories
                },
                success: function(data) {
                    console.log("Finsihed!")
                    console.log(data);
                    var result_found = false;
                    var topSearchArray = data.split("&_&");
                    var secondSearchArray = "null";
                    var thirdSearchArray = "null";
                    console.log("processing 0");
                    if (topSearchArray.length < 1) {
                        document.getElementById("loading_text").innerHTML = "No results found.";
                    }
                    else {
                        for (i = 0; i < topSearchArray.length; i++) {
                            console.log("Processing1");
                            secondSearchArray = topSearchArray[i].split(":!:");
                            if (secondSearchArray[0] == "district") {
                                thirdSearchArray = secondSearchArray[1].split("~-~");
                                var rootElement = document.getElementById("district_result_container");
                                console.log("Processing2");
                                for (j = 0; j < thirdSearchArray.length; j++) {
                                    console.log("Processing 3");
                                    var fourthSearchArray = thirdSearchArray[j].split("#-#");
                                    var element = `
                                    <div id="`+ fourthSearchArray[1] +`_search_link" onclick="document.forms["`+ fourthSearchArray[0] +`_form"].submit();" class="search_submit_div">
                                    <form action="/pages/info/info.php" method="POST" class="search_result" id="`+ fourthSearchArray[0] +`_form">
                                        <input type="hidden" name="type" value="district">
                                        <input type="hidden" name="id" value="`+ fourthSearchArray[0] +`">
                                        <p>`+ fourthSearchArray[1] +`</p>
                                    </form>
                                    </div>`;
                                    console.log(element);
                                    rootElement.innerHTML += element;
                                    console.log("Processing 4");
                                }
                                console.log("Processing 5")
                                document.getElementById("district_result_container").style.visibility = "visible";
                            }
                        }
                        console.log("Processing 6");
                        document.getElementById("loading_text").innerHTML = "";
                    }
                }
            });
        }
    }
}
function toggle_all() {
    if (document.getElementById("search_all_checkbox").checked) {
        document.getElementById("search_district_checkbox").disabled = true;
        document.getElementById("search_street_unit_checkbox").disabled = true;
        document.getElementById("search_streets_checkbox").disabled = true;
        document.getElementById("search_building_checkbox").disabled = true;
    }
    else {
        document.getElementById("search_district_checkbox").disabled = false;
        document.getElementById("search_street_unit_checkbox").disabled = false;
        document.getElementById("search_streets_checkbox").disabled = false;
        document.getElementById("search_building_checkbox").disabled = false;
    }
}
document.getElementById("search_all_checkbox").checked = true;
document.getElementById("search_district_checkbox").checked = true;
document.getElementById("search_street_unit_checkbox").checked = true;
document.getElementById("search_streets_checkbox").checked = true;
document.getElementById("search_building_checkbox").checked = true;
