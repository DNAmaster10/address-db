function changeStreetUnits() {
    var district = document.getElementById("district_select").value;
    console.log(district);
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
