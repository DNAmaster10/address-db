const loadingText = ["Searching...", "Loading...", "Foraging...", "Processing...","Examining...","Finding..."];

function submit_search() {
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
                                var rootElement = $("district_result_container");
                                console.log("Processing2");
                                for (j = 0; j < thirdSearchArray.length; j++) {
                                    console.log("Processing 3");
                                    thirdSearchArray[j].split("#-#");
                                    var element = `
                                    <form onclick="javascript:this.form.submit();" action="/pages/info/info.php" method="POST" class="search_result">
                                        <input type="hidden" name="type" value="district">
                                        <input type="hidden" name="id" value="`+ thirdSearchArray[0] +`">
                                        <p>`+ thirdSearchArray[1] +`</p>
                                    </form>`;
                                    rootElement.append(element);
                                    console.log("Processing 4");
                                }
                            }
                        }
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
