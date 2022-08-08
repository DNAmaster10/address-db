function changeStreetUnits() {
    var district = document.getElementById("district_select").value;
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
        document.getElementById("furniture_yes").disabled = false;
        document.getElementById("furniture_no").disabled = false;
        document.getElementById("apartment_bedroom_ammount").disabled = false;
        document.getElementById("increment_apartment_bedroom_ammount_1").disabled = false;
        document.getElementById("increment_apartment_bedroom_ammount_-1").disabled = false;
    }
    else {
        document.getElementById("furniture_yes").disabled = true;
        document.getElementById("furniture_no").disabled = true;
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

changeStreetUnits();
show_house();
show_apartment();
