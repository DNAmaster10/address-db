function addBuildingType() {
    var rootElement = $("#building_type_list_container");
    var buildingType = document.getElementById("add_building_type").value;
    var buildingTypeText = buildingType.replace("_", " ");
    buildingTypeText = buildingTypeText.capitalize();
    var current_building_list = document.getElementById("building_type_list_hidden").value;
    if (current_building_list.includes(buildingType)) {
        return (false);
    }
    else if (buildingType != "commercial" && buildingType != "franchise") {
        var element = `
            <div id="`+buildingType+`_container" class="building_type_container">
            <p class="inline">Type: `+buildingTypeText+` | Ammount: </p>
            <input type="text" name="`+buildingType+`_ammount" value="1" placeholder="ammount" class="inline" id="`+buildingType+`_ammount_input">
            <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="`+buildingType+`_minus_ammount_button">+1</button>
            <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="`+buildingType+`_plus_ammount_button">-1</button>
            <button type="button" class="inline" onclick="remove_type(this)" id="`+buildingType+`_remove_type">X</button>
        </div>`;
    }
    else if (buildingType == "franchise") {
        var element = `
        <div id="franchise_container" class="building_type_container">
            <p class="inline">Type: Franchise | Ammount: </p>
            <input type="text" name="franchise_ammount" value="1" placeholder="ammount" class="inline" id="franchise_ammount_input">
            <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="franchise_minus_ammount_button">+1</button>
            <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="franchise_plus_ammount_button">-1</button>
            <p class="inline"> | Owner(s): </p>
            <input type="text" placeholder="DNAmaster10,Needn_NL" name="franchise_owners">
            <button type="button" class="inline" onclick="remove_type(this)" id="franchise_remove_type">X</button>
        </div>
        `;
    }
    else {
        var element = `
        <div id="commercial_container" class="building_type_container">
            <p class="inline">Type: Commercial | Ammount: </p>
            <input type="text" name="commercial_ammount" value="1" placeholder="ammount" class="inline" id="commercial_ammount_input">
            <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="commercial_minus_ammount_button">+1</button>
            <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="commercial_plus_ammount_button">-1</button>
            <div id="commerce" class="inline">
                <input type="text" name="commerce_types" placeholder="Food,Car Parts">
            </div>
            <button type="button" class="inline" onclick="remove_type(this)" id="commercial_remove_type">X</button>
        </div>
        `;
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
    current_count = parseInt(current_count);
    var new_count = current_count + increment_ammount;
    if (new_count == 0 || isNaN(new_count)) {
        new_count = 1;
    }
    document.getElementById(building_type + "_ammount_input").value = new_count;
}

function remove_type(element) {
    var button_id = element.id;
    var id_array = button_id.split("_");
    var building_type = id_array[0];
    document.getElementById(building_type + "_container").remove();
    var current_building_list = document.getElementById("building_type_list_hidden").value;
    if (current_building_list.includes("#-#" + building_type)) {
        current_building_list = current_building_list.replace("#-#" + building_type, "");
    }
    else {
        current_building_list = current_building_list.replace(building_type, "");
    }
    document.getElementById("building_type_list_hidden").value = current_building_list;
}
