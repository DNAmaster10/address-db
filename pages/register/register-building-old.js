var hasCommercial = false;
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
function removeBuildingType(building_type) {
    document.getElementById("type_" + building_type + "_container").remove();

    var current_types = document.getElementById("building_types").value;
    var new_types = current_types.replace("#-#" + building_type, "");
    document.getElementById("building_types").value = new_types;
    if (building_type == "commercial") {
        document.getElementById("commerce_type").style.visibility = "hidden";
    }
}
function alterCount(element) {
    var increment_ammount = element.value;
    increment_ammount = parseInt(increment_ammount);
    var id = element.id;
    var id_array = id.split("#");
    var building_type = id_array[1];
    current_count = document.getElementById("typeAmmountP_" + building_type).value;
    current_count = parseInt(current_count);
    new_count = current_count + increment_ammount;
    document.getElementById("typeAmmountP_" + building_type).value = new_count;
}
function addBuildingType() {
    var select_element = document.getElementById("add_building_type");
    var building_type = select_element.options[select_element.selectedIndex].value;
    if (!document.getElementById("type_" + building_type + "_container")) {
        var element = document.createElement("div");
        element.setAttribute("id","type_" + building_type + "_container");
        element.setAttribute("class","building_type_container");
        document.getElementById("building_type_list_container").appendChild(element);

        var element = document.createElement("p");
        element.setAttribute("id","type_" + building_type + "_p");
        element.setAttribute("class","inline");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("type_" + building_type + "_p").innerHTML = building_type;

        var element = document.createElement("button");
        element.setAttribute("onclick", "alterCount(this)");
        element.setAttribute("class", "inline");
        element.setAttribute("id","minus10Button#" + building_type);
        element.setAttribute("value", "-10");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("minus10Button#" + building_type).innerHTML = "-10";

        var element = document.createElement("button");
        element.setAttribute("onclick", "alterCount(this)");
        element.setAttribute("class", "inline");
        element.setAttribute("id","minus1Button#" + building_type);
        element.setAttribute("value", "-1");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("minus1Button#" + building_type).innerHTML = "-1";

        var element = document.createElement("input");
        element.setAttribute("id","typeAmmountP_" + building_type);
        element.setAttribute("class","inline");
        element.setAttribute("name","ammount_" + building_type);
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("typeAmmountP_" + building_type).value = "1";

        var element = document.createElement("button");
        element.setAttribute("onclick", "alterCount(this)");
        element.setAttribute("class", "inline");
        element.setAttribute("id","plus1Button#" + building_type);
        element.setAttribute("value","1");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("plus1Button#" + building_type).innerHTML = "+1";

        var element = document.createElement("button");
        element.setAttribute("onclick", "alterCount(this)");
        element.setAttribute("class", "inline");
        element.setAttribute("id","plus10Button#" + building_type);
        element.setAttribute("value","10");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("plus10Button#" + building_type).innerHTML = "+10";

        var element = document.createElement("p");
        element.setAttribute("class","owners_p");
        element.setAttribute("id","franchise_owners");

        var element = document.createElement("input");
        element.setAttribute("type", "text");
        element.setAttribute("placeholder","DNAmaster10,Needn_NL");

        var element = document.createElement("button");
        element.setAttribute("id", "remove_building_button_" + building_type);
        element.setAttribute("onclick","removeBuildingType('"+ building_type +"')");
        element.setAttribute("class","inline");
        document.getElementById("type_" + building_type + "_container").appendChild(element);
        document.getElementById("remove_building_button_" + building_type).innerHTML = "X";

        var current_types = document.getElementById("building_types").value;
        var new_types = current_types.concat("#-#",building_type);
        document.getElementById("building_types").value = new_types;
    }
    if (building_type == "commercial") {
        hasCommercial = true;
        addCommercialTypeInput();
    }
}

function add_commerce_item() {
    var item = document.getElementById("commerce_type_text_input").value;
    document.getElementById("commerce_type_text_input").value = "";
    var old_items = document.getElementById("commerce_items_hidden").value;
    var new_items = old_items + "#-#" + item;
    document.getElementById("commerce_items_hidden").value = new_items;
    var old_items_p = document.getElementById("commerce_p").innerHTML;
    if (old_items_p == "") {
        new_items_p = item;
    }
    else {
        new_items_p = old_items_p + ", " + item;
    }
    document.getElementById("commerce_p").innerHTML = new_items_p;
}
function reset_commerce_items() {
    document.getElementById("commerce_type_text_input").value = "";
    document.getElementById("commerce_items_hidden").value = "";
    document.getElementById("commerce_p").innerHTML = "";
}
function addCommercialTypeInput() {
    document.getElementById("commerce_type").style.visibility = "visible";
}
changeStreetUnits();
