//Fetch building type data

const building_id = document.getElementById("building_id_p").innerHTML;
$.ajax({
    url: "/pages/info/handle/handle_building_type.php",
    type: "POST",
    data: {building_id:building_id},
    success: function(data) {
        console.log(data);
        var rootElement = $("#logged_in_container")
        if (!data.length > 0) {
            var element = "<p>No type data was returned</p>";
            rootElement.append(element);
        }
        else {
            type_array = data.split("-@-");
            var current_type;
            var current_type_array;
            for (var i = 0; i < type_array.length; i++) {
                current_type = type_array[i];
                current_type_array = current_type.split(";");
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
                else {
                    if (!current_type_array[0].length < 1) {
                        var element = `
                        <div id="` + current_type_array[0] + `" class="type_container">
                            <p>` + current_type_array[0] + `(s)</p>
                            <p>Ammount: ` + current_type_array[1]`</p>
                        </div>
                        `
                    }
                    else {
                        console.log("An error occured");
                    }
                }
                rootElement.append(element);
            }
        }
    }
})