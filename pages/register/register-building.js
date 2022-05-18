document.getElementById("district_select").onchance = changeStreetUnits;
function changeStreetUnits() {
    var district = this.value
    console.log(value);
    $.ajax({
        url: "/pages/register/handle/get_units.php",
        type: "GET",
        data: {district:district},
        success: function(data) {
            console.log(data);
        }
    });
}
