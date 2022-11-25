function fetchExistingTypeArray() {
    var building_id = document.getElementById("building_id_p").innerHTML;
    $.ajax({
        url: "/pages/info/handle/handle_building_type.php",
        type: "POST",
        data: {building_id:building_id},
        success: function(data) {
            addExistingTypes(data);
        }
    });
}
function addExistingTypes(types) {
    type_array = types.split("-@-");
    var added_buildings = ",";
    var current_type_array;
    for (var i = 0; i < type_array.length; i++) {
        current_type_array = type_array[i].split(";");
        if (!(added_buildings.includes(current_type_array[0]))) {
            if (current_type_array[0] == "franchise") {
                if (current_type_array[3].includes("-@-")) {
                    var commerce_types = current_type_array[3].split("-@-");
                    var franchise_commerce = commerce_types[1];
                }
                else {
                    var franchise_commerce = current_type_array[3];
                }
                var element = `
                <div id="franchise_container" class="building_type_container">
                    <p class="inline">Type: Franchise | Ammount: ` + current_type_array[1] + `</p>
                    <input type="text" name="franchise_ammount" value="` + current_type_array[1] + `" placeholder="ammount" class="inline" id="franchise_ammount_input">
                    <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="franchise#minus_ammount_button">+1</button>
                    <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="franchise#plus_ammount_button">-1</button>
                    <p class="inline"> | Owner(s): </p>
                    <input type="text" placeholder="DNAmaster10,Needn_NL" name="franchise_owners" value="` + current_type_array[2] + `">
                    <p class="inline"> | Commerce types: </p>
                    <div id="commerce_franchise" class="inline">
                        <input type="text" name="commerce_types_franchise" placeholder="Food,Car Parts" value="` + franchise_commerce + `">
                    </div>
                    <button type="button" class="inline del_button" onclick="remove_type(this)" id="franchise#remove_type">X</button>
                </div>
                `;
            }
            else if (current_type_array[0] == "commercial") {
                if (current_type_array[2].includes("-@-")) {
                    var commerce_types = current_type_array[2].split("-@-");
                    var commercial_commerce = commerce_types[0];
                }
                else {
                    var commercial_commerce = current_type_array[2];
                }
                var element = `
                <div id="commercial_container" class="building_type_container">
                    <p class="inline">Type: Commercial | Ammount: ` + current_type_array[1] + `</p>
                    <input type="text" name="commercial_ammount" value="` + current_type_array[1] + `" placeholder="ammount" class="inline" id="commercial_ammount_input">
                    <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="commercial#minus_ammount_button">+1</button>
                    <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="commercial#plus_ammount_button">-1</button>
                    <p class="inline"> | Commerce types: </p>
                    <div id="commerce" class="inline">
                        <input type="text" name="commerce_types" placeholder="Food,Car Parts" value="` + commercial_commerce + `">
                    </div>
                    <button type="button" class="inline del_button" onclick="remove_type(this)" id="commercial#remove_type">X</button>
                </div>
                `;
            }
            else {
                var element = `
                <div id="` + current_type_array[0] + `_container" class="building_type_container">
                    <p class="inline">Type: `+current_type_array[0]+` | Ammount: `+current_type_array[1]+`</p>
                    <input type="text" name="`+current_type_array[0]+`_ammount" value="`+current_type_array[1]+`" placeholder="ammount" class="inline" id="`+current_type_array[0]+`_ammount_input">
                    <button type="button" onclick="change_building_count(this)" value="1" class="inline" id="`+current_type_array[0]+`#minus_ammount_button">+1</button>
                    <button type="button" onclick="change_building_count(this)" value="-1" class="inline" id="`+current_type_array[0]+`#plus_ammount_button">-1</button>
                    <button type="button" class="inline del_button" onclick="remove_type(this)" id="`+current_type_array[0]+`#remove_type">X</button>
                </div>`;
            }
        }
    }
}