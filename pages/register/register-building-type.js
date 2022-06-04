function addBuildingTypeOld() {
  const domContainer = document.getElementById("building_type_list_container");
  const root = ReactDOM.createRoot(domContainer);
  const buildingType = document.getElementById("add_building_type").value;
  const divId = buildingType + "Container";
  console.log("got to 3");
  if (buildingType != "commercial" && buildingType != "franchise") {
    const element = /*#__PURE__*/React.createElement("div", {
      id: divId
    });
    root.render(element);
    console.log("got to 1");
  } else {
    const element = /*#__PURE__*/React.createElement("div", {
      id: divId
    });
    root.render(element);
    console.log("got to 2");
  }
}

function addBuildingType() {
    var rootElement = $("#building_type_list_container");
    var buildingType = document.getElementById("add_building_type").value;
    var element = "<div id='" + buildingType + "_container'><p>This is a test</p></div>";
    rootElement.append(element);
}
