function addBuildingType() {
    var rootElement = $("#building_type_list_container");
    var buildingType = document.getElementById("add_building_type").value;
    if (buildingType != "commercial" && buildingType != "franchise") {
        var element = `
        <div id='` + buildingType + `_container'>
            <p>This is a test</p>
        </div>`;
    }
    rootElement.append(element);
}
