function changeStreetUnits() {
    var district = this.value
    console.log(value);
    console.log("hello");
    $.ajax({
        url: "/pages/register/handle/get_units.php",
        type: "GET",
        data: {district:district},
        success: function(data) {
            console.log(data);
        }
    });
}
