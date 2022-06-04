function addBuildingType() {
  const domContainer = document.getElementById("building_type_list_container");
  const root = ReactDOM.createRoot(domContainer);
  const buildingType = document.getElementById("add_building_type").value;
  const divId = buildingType + "Container";

  if (buildingType != "commercial" && buildingType != "franchise") {
    const element = /*#__PURE__*/React.createElement("div", {
      id: divId
    });
  } else {
    const element = /*#__PURE__*/React.createElement("div", {
      id: divId
    });
  }

  root.render(element);
}
