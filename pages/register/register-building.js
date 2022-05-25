function changeStreetUnits() {
    var district = document.getElementById("district_select").value;
    console.log("running");
    $.ajax({
        url: "/pages/register/handle/get_units.php",
        type: "GET",
        data: {district:district},
        success: function(data) {
            if (data == "error") {
                console.log("An error occured.");
            }
            else {
                var street_unit_array = data.split("#-#");
                selectionBox = document.getElementById("street_unit_select");
                while (selectionBox.options.length > 0) {
                    selectionBox.remove(0);
                }
                street_unit_count = street_unit_array.length;
                if (street_unit_count < 1) {
                    console.log("There are no street units in that district");
                }
                else {
                    for (var i=0; i<street_unit_count -1; i++) {
                        var newOption = new Option (street_unit_array[i],street_unit_array[i]);
                        selectionBox.add(newOption,undefined);
                    }
                }
                data = undefined;
            }
        }
    });
}

function get_details() {
    var co_ords_string = document.getElementById("coords").value;
    if (co_ords_string.length < 3) {
        console.log("Co-ords entered too short");
    }
    else {
        $.ajax({
            url: "/pages/register/handle/get_details.php",
            type: "GET",
            data: {coords:co_ords_string},
            success: function(data) {
                if (data == "error1") {
                    console.log("No district or street unit was found");
                }
                else {
                    var details = data.split("#-#");
                    selectionBox = document.getElementById("district_select");
                    selectionBox.value = details[0];

                    selectionBox = document.getElementById("street_unit_select");
                    selectionBox.value = details[1];
                }
                data = undefined;
            }
        });
    }
}

function typeChange(checkbox) {
    if (checkbox.checked == "checked") {
        console.log("unchecking");
        checkbox.checked = "false";
    }
    value = checkbox.value;
    console.log(value);
}

changeStreetUnits();
