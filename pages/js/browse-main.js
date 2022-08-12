function submit_search() {
    if (document.getElementById("search_all_checkbox").checked) {
        var search_categories = "all";
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
        if (!search_categories == ",") {
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
                        console.log(data);
                    }
                });
            }
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
        document.getElementById("search_street_unit_checkbox").disable = false;
        document.getElementById("search_streets_checkbox").disabled = false;
        document.getElementById("search_building_checkbox").disabled = false;
    }
}
document.getElementById("search_all_checkbox").checked = true;
document.getElementById("search_district_checkbox").checked = true;
document.getElementById("search_street_unit_checkbox").checked = true;
document.getElementById("search_streets_checkbox").checked = true;
document.getElementById("search_building_checkbox").checked = true;
