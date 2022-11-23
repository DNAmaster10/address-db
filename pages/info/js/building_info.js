//Fetch building type data

const building_id = document.getElementById("building_id_p").innerHTML;
$.ajax({
    url: "/pages/info/handle/handle_building_type.php",
    type: "POST",
    data: {building_id:building_id},
    success: function(data) {
        console.log(data);
    }
})