function addBuildingType() {
    const domContainer = document.getElementById("building_type_list_container");
    const root = ReactDOM.createRoot(domContainer);

    const buildingType = document.getElementById("add_building_type").value;
    const div_id = buildingType + "Container";
    if (buildingType != "commercial" or buildingType != "franchise") {
        const element = <div id={buildingType}>
    }
    root.render(element);
}
