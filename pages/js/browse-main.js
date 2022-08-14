const loadingText = ["Searching...", "Loading...", "Foraging...", "Processing...","Examining...","Finding..."];

function submit_search() {
    document.getElementById("district_result_container").style.visibility = "hidden";
    document.getElementById("street_units_result_container").style.visibility = "hidden";
    document.getElementById("streets_result_container").style.visibility = "hidden";
    document.getElementById("building_result_container").style.visibility = "hidden";
    document.getElementById("district_result_container").innerHTML = "";
    document.getElementById("street_units_result_container").innerHTML = "";
    document.getElementById("streets_result_container").innerHTML = "";
    document.getElementById("building_result_container").innerHTML = "";

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
                    if (topSearchArray.length < 1) {
                        document.getElementById("loading_text").innerHTML = "No results found.";
                    }
                    else {
                        for (i = 0; i < topSearchArray.length; i++) {
                            secondSearchArray = topSearchArray[i].split(":!:");
                            if (secondSearchArray[0] == "district") {
                                thirdSearchArray = secondSearchArray[1].split("~-~");
                                var rootElement = document.getElementById("district_result_container");
                                var element = "<h2>Districts</h2>";
                                rootElement.innerHTML += element;
                                var elementCount = 0;
                                for (j = 1; j < thirdSearchArray.length - 1; j++) {
                                    var fourthSearchArray = thirdSearchArray[j].split("#-#");
                                    elementCount++;
                                    var element = `
                                    <div id="`+ fourthSearchArray[1] +`_district_search_link" onclick="document.forms['`+ fourthSearchArray[1] +`_district_form'].submit();" class="search_submit_div">
                                        <form action="/pages/info/district_info.php" method="POST" class="search_result" id="`+ fourthSearchArray[1] +`_district_form">
                                            <input type="hidden" name="type" value="district">
                                            <input type="hidden" name="id" value="`+ fourthSearchArray[1] +`">
                                            <p>`+ fourthSearchArray[0] +`</p>
                                        </form>
                                    </div>`;
                                    rootElement.innerHTML += element;
                                }
                                if (elementCount > 0) {
                                    document.getElementById("district_result_container").style.visibility = "visible";
                                }
                            }
                            else if (secondSearchArray[0] == "street_units") {
                                thirdSearchArray = secondSearchArray[1].split("~-~");
                                var rootElement = document.getElementById("street_units_result_container");
                                var element = "<h2>Street Units</h2>";
                                rootElement.innerHTML += element;
                                var elementCount = 0;
                                for (j = 1; j < thirdSearchArray.length - 1; j++) {
                                    var fourthSearchArray = thirdSearchArray[j].split("#-#");
                                    elementCount++;
                                    var element = `
                                    <div id="`+ fourthSearchArray[1] +`_street_unit_search_link" onclick="document.forms['`+ fourthSearchArray[1] +`_street_unit_form'].submit();" class="search_submit_div">
                                        <form action="/pages/info/street_unit_info.php" method="POST" class="search_result" id="`+ fourthSearchArray[1] +`_street_unit_form">
                                            <input type="hidden" name="type" value="street_unit">
                                            <input type="hidden" name="id" value="`+ fourthSearchArray[1] +`">
                                            <p>`+ fourthSearchArray[0] +`</p>
                                        </form>
                                    </div>`;
                                    rootElement.innerHTML += element;
                                }
                                if (elementCount > 0) {
                                    document.getElementById("street_units_result_container").style.visibility = "visible";
                                }
                            }
                            else if (secondSearchArray[0] == "streets") {
                                thirdSearchArray = secondSearchArray[1].split("~-~");
                                var rootElement = document.getElementById("streets_result_container");
                                var element = "<h2>Streets / Roads</h2>";
                                rootElement.innerHTML += element;
                                var elementCount = 0;
                                for (j = 1; j < thirdSearchArray.length - 1; j++) {
                                    var fourthSearchArray = thirdSearchArray[j].split("#-#");
                                    elementCount++;
                                    var element = `
                                    <div id="`+ fourthSearchArray[1] +`_street_search_link" onclick="document.forms['`+ fourthSearchArray[1] +`_street_form'].submit();" class="search_submit_div">
                                        <form action="/pages/info/street_info.php" method="POST" class="search_result" id="`+ fourthSearchArray[1] +`_street_form">
                                            <input type="hidden" name="type" value="street">
                                            <input type="hidden" name="id" value="`+ fourthSearchArray[1] +`">
                                            <p>`+ fourthSearchArray[0] +`</p>
                                        </form>
                                    </div>`;
                                    rootElement.innerHTML += element;
                                }
                                if (elementCount > 0) {
                                    document.getElementById("streets_result_container").style.visibility = "visible";
                                }
                            }
                            else if (secondSearchArray[0] == "buildings") {
                                thirdSearchArray = secondSearchArray[1].split("~-~");
                                var rootElement = document.getElementById("building_result_container");
                                var element = "<h2>Buildings</h2>";
                                rootElement.innerHTML += element;
                                var elementCount = 0;
                                for (j = 1; j < thirdSearchArray.length - 1; j++) {
                                    var fourthSearchArray = thirdSearchArray[j].split("#-#");
                                    elementCount++;
                                    var element = `
                                    <div id="`+ fourthSearchArray[1] +`_building_search_link" onclick="document.forms['`+ fourthSearchArray[1] +`_building_form'].submit();" class="search_submit_div">
                                        <form action="/pages/info/building_info.php" method="POST" class="search_result" id="`+ fourthSearchArray[1] +`_building_form">
                                            <input type="hidden" name="type" value="building">
                                            <input type="hidden" name="id" value="`+ fourthSearchArray[1] +`">
                                            <p>`+ fourthSearchArray[0] +`</p>
                                        </form>
                                    </div>`;
                                    rootElement.innerHTML += element;
                                }
                                if (elementCount > 0) {
                                    document.getElementById("building_result_container").style.visibility = "visible";
                                }
                            }
                        }
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
