function addBuildingType() {
    var rootElement = $("#building_type_list_container");
    var buildingType = document.getElementById("add_building_type").value;
    if (buildingType != "commercial" && buildingType != "franchise") {
        var element = `
        <div id='` + buildingType + `_container' class='building_type_container'>
            <p>`+buildingType+`</p>
        </div>`;
    }
    else if (buildingType == "franchise") {
        var element = `
        <div id="franchise_container" class="building_type_container">
            <p>Franchise</p>
        </div>
        `
    }
    else {
        var element = `
        <div id="commercial_container" class="building_type_container">
            <p>Type: Commercial | Ammount: </p>
            <input type="text" name="commercial_ammount" value="1" placeholder="ammount" class="inline" id="commercial_ammount_input">
            <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="commercial_minus_ammount_button">+1</button>
            <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="commercial_plus_ammount_button">-1</button>
            <div id="commerce>
            </div>
        </div>
        `
    }
    var current_building_list = document.getElementById("building_type_list_hidden").value;
    if (current_building_list == "") {
        current_building_list = current_building_list = buildingType;
        document.getElementById("building_type_list_hidden").value = current_building_list;
    }
    else {
        current_building_list = current_building_list + "#-#" + buildingType;
        document.getElementById("building_type_list_hidden").value = current_building_list;
    }
    rootElement.append(element);
}

function change_building_count(element) {
    var increment_ammount = element.value;
    increment_ammount = parseInt(increment_ammount);
    var button_id = element.id;
    var id_array = button_id.split("_");
    var building_type = id_array[0];
    var current_count = document.getElementById(building_type + "_ammount_input").value;
    var new_count = current_count + increment_ammount;
    document.getElementById(building_type + "_ammount_input").value = new_count;
}
