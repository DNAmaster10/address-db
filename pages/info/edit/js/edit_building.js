function changeStreetUnits() {
    //Gathers street units depending on selected district and updates street unit dropdown
    var district = document.getElementById("district_select").value;
    $.ajax({
        url: "/pages/register/handle/get_units.php",
        type: "GET",
        data: {district:district},
        success: function(data) {
            if (data == "error") {
                console.log("An error occured (1)");
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
                    for (var i = 0; i < street_unit_count - 1; i++) {
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
    var x_coord = document.getElementById("x_coord").value;
    var y_coord = document.getElementById("y_coord").value;
    var co_ords_string = x_coord + y_coord;
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

function show_house() {
    if (document.getElementById("house_yes_no").checked) {
        document.getElementById("house_bedroom_ammount").disabled = false;
        document.getElementById("increment_house_bedroom_ammount_1").disabled = false;
        document.getElementById("increment_house_bedroom_ammount_-1").disabled = false;
    }
    else {
        document.getElementById("house_bedroom_ammount").disabled = true;
        document.getElementById("increment_house_bedroom_ammount_1").disabled = true;
        document.getElementById("increment_house_bedroom_ammount_-1").disabled = true;
    }
}

function show_apartment() {
    if (document.getElementById("apartment_yes_no").checked) {
        document.getElementById("furniture_ammount").disabled = false;
        document.getElementById("increment_apartment_furniture_ammount_1").disabled = false;
        document.getElementById("increment_apartment_furniture_ammount_-1").disabled = false;
        document.getElementById("apartment_bedroom_ammount").disabled = false;
        document.getElementById("increment_apartment_bedroom_ammount_1").disabled = false;
        document.getElementById("increment_apartment_bedroom_ammount_-1").disabled = false;
    }
    else {
        document.getElementById("furniture_ammount").disabled = true;
        document.getElementById("increment_apartment_furniture_ammount_1").disabled = true;
        document.getElementById("increment_apartment_furniture_ammount_-1").disabled = true;
        document.getElementById("apartment_bedroom_ammount").disabled = true;
        document.getElementById("increment_apartment_bedroom_ammount_1").disabled = true;
        document.getElementById("increment_apartment_bedroom_ammount_-1").disabled = true;
    }
}

function increment_house_bedroom_ammount(element) {
    var increment_ammount = element.value;
    increment_ammount = parseInt(increment_ammount);
    var current_count = document.getElementById("house_bedroom_ammount").value;
    current_count = parseInt(current_count);
    current_count = current_count + increment_ammount;
    if (current_count < 0) {
        document.getElementById("house_bedroom_ammount").value = "0";
    }
    else {
        document.getElementById("house_bedroom_ammount").value = current_count;
    }
}

function increment_apartment_bedroom_ammount(element) {
    var increment_ammount = element.value;
    increment_ammount = parseInt(increment_ammount);
    var current_count = document.getElementById("apartment_bedroom_ammount").value;
    current_count = parseInt(current_count);
    current_count = current_count + increment_ammount;
    if (current_count < 0) {
        document.getElementById("apartment_bedroom_ammount").value = "0";
    }
    else {
        document.getElementById("apartment_bedroom_ammount").value = current_count;
    }
}

function increment_apartment_furniture(element) {
    var increment_ammount = element.value;
    increment_ammount = parseInt(increment_ammount);
    var current_count = document.getElementById("furniture_ammount").value;
    current_count = parseInt(current_count);
    current_count = current_count + increment_ammount;
    if (current_count < 0) {
        document.getElementById("furniture_ammount").value = "0";
    }
    else {
        document.getElementById("furniture_ammount").value = current_count;
    }
}

function alter_edit_details() {
    //If cencus data is set, fill it in
    var house_data = document.getElementById("contains_house_data").innerHTML;
    if (!(house_data == "false")) {
        house_data = house_data.split(",");
        document.getElementById("house_yes_no").checked = true;
        document.getElementById("house_bedroom_ammount").value = house_data[1];
    }
    var apartment_data = document.getElementById("contains_apartment_data").innerHTML;
    if (!(apartment_data == "false")) {
        apartment_data = apartment_data.split(",");
        document.getElementById("apartment_yes_no").checked = true;
        document.getElementById("furniture_ammount").value = apartment_data[2];
        document.getElementById("apartment_bedroom_ammount").value = apartment_data[1];
    }

    //Change district and street unit
    var district_street_unit_data = document.getElementById("edit_district_street_unit_info").innerHTML;
    district_street_unit_data = district_street_unit_data.split(",");
    var district = district_street_unit_data[0];
    var street_unit = district_street_unit_data[1];
    document.getElementById("district_select").value = district;
    document.getElementById("street_unit_select").value = street_unit;
}

alter_edit_details();
show_house();
show_apartment();
changeStreetUnits();