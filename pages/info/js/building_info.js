//Fetch building type data

const building_id = document.getElementById("building_id_p").innerHTML;
$.ajax({
    url: "/pages/info/handle/handle_building_type.php",
    type: "POST",
    data: {building_id:building_id},
    success: function(data) {
        var rootElement = $("#inner_building_container")
        if (!data.length > 0) {
            var element = "<p>No type data was returned</p>";
            rootElement.append(element);
        }
        else {
            type_array = data.split("-@-");
            var added_buildings = ",";
            var current_type_array;
            for (var i = 0; i < type_array.length; i++) {
                current_type_array = type_array[i].split(";");
                if (!(added_buildings.includes(current_type_array[0]))) {
                    if (current_type_array[0] == "franchise") {
                        var element = `
                        <div id="franchise_type" class="type_container">
                            <p>Franchise(s)</p>
                            <p>Ammount: ` + current_type_array[1] + `</p>
                            <p>Franchise Owner(s): ` + current_type_array[2] + `</p>
                            <p>Commerce Type(s): ` + current_type_array[3] + `</p>
                        </div>
                        `;
                    }
                    else if (current_type_array[0] == "house") {
                        var element = `
                        <div id="house_type" class="type_container">
                            <p>House(s)</p>
                            <p>Ammount: ` + current_type_array[1] + `</p>
                            <p>Additional Bedrooms: ` + current_type_array[2] + `</p>
                        </div>
                        `;
                    }
                    else if (current_type_array[0] == "apartment") {
                        var element = `
                        <div id="apartment_type" class="type_container">
                            <p>Apartment(s)</p>
                            <p>Ammount: ` + current_type_array[1] + `</p>
                            <p>Additional Bedrooms: ` + current_type_array[2] + `</p>
                            <p>Additional Furniture: ` + current_type_array[3] + `</p>
                        </div>
                        `;
                    }
                    else if (current_type_array[0] == "commercial") {
                        var element = `
                        <div id="apartment_type" class="type_container">
                            <p>Commercial</p>
                            <p>Ammount: ` + current_type_array[1] + `</p>
                            <p>Commerce type(s): ` + current_type_array[2] + `
                        </div>
                        `;
                    }
                    else {
                        if (!((current_type_array[0].length) < 1)) {
                            var element = `
                            <div id="` + current_type_array[0] + `" class="type_container">
                                <p>` + current_type_array[0] + `(s)</p>
                                <p>Ammount: ` + current_type_array[1] + `</p>
                            </div>
                            `
                        }
                        else {
                            console.log("An error occured");
                        }
                    }
                    added_buildings = added_buildings + current_type_array[0] + ",";
                    rootElement.append(element);
                }
            }
        }
    }
})