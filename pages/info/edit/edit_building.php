<?php
    //start sessions and make sure user is logged in
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    
    //make sure ID is set in GET request
    if (!isset($_GET["id"])) {
        $_SESSION["generic_error"] = "No building ID was set in the GET request";
        header ("location: /pages/error/generic-error.php");
        die();
    }

    //if ID is set, check it's valid
    if (!is_numeric($_GET["id"])) {
        $_SESSION["generic_error"] = "ID invalid: is not numeric";
        header ("location: /pages/error/generic-error.php");
        die();
    }

    //convert ID to integer
    $id = intval($_GET["id"]);

    //check ID exists in database and fetch building name
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic_error"] = "Building not found in database";
        header ("location: /pages/error/generic-error.php");
        die();
    }
    else {
        $building_name = $result;
    }
    unset($result);

    //Get co-ords
    $stmt = $conn->prepare("SELECT x,y FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $xcoord = $row["x"];
        $ycoord = $row["y"];
    }
    $stmt->close();
    unset ($result);

    //fetch street
    $stmt = $conn->prepare("SELECT parent_street FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street_name = false;
    }
    else {
        $street_name = $result;
    }
    unset ($result);

    //fetch builders
    $stmt = $conn->prepare("SELECT builders FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $has_builders = false;
    }
    else {
        $has_builders = true;
        $builders = $result;
    }
    unset ($result);

    //fetch construction date
    $stmt = $conn->prepare("SELECT construction_date FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $has_date = false;
    }
    else {
        $has_date = true;
        $date = $result;
    }
    unset ($result);

    //Check if house data is set
    $stmt = $conn->prepare("SELECT contains_house FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result || $result == "no") {
        $has_house_data = false;
    }
    else {
        //fetch house data if it is contained
        unset($result);
        $stmt = $conn->prepare("SELECT other_bedrooms_house FROM buildings WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (!$result) {
            $has_house_data = false;
        }
        else {
            $has_house_data = true;
            $other_bedrooms_house = $result;
        }
    }
    unset($result);

    //Check if apartment data is set
    $stmt = $conn->prepare("SELECT contains_apartment FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result || $result == "no") {
        $has_apartment_data = false;
    }
    else {
        $stmt = $conn->prepare("SELECT furniture_apartment,other_bedrooms_apartment FROM buildings WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $furniture_apartment = $row["furniture_apartment"];
            $other_bedrooms_apartment = $row["other_bedrooms_apartment"];
        }
        $has_apartment_data = true;
    }
    unset($result);

    //Fetch description
    $stmt = $conn->prepare("SELECT description FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $contains_description = false;
    }
    else {
        $contains_description = true;
        $description = $result;
    }
    unset ($result);

    //Fetch postcode char
    $stmt = $conn->prepare("SELECT postcode_char FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $contains_postcode_char = false;
    }
    else {
        $contains_postcode_char = true;
        $postcode_char = $result;
    }
    unset ($result);

    //Fetch street unit and district
    $stmt = $conn->prepare("SELECT parent_street_unit,parent_district FROM buildings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $parent_street_unit = $row["parent_street_unit"];
        $parent_district = $row["parent_district"];
    }
    $stmt->close();
    unset ($result);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Editing <?php echo ($building_name); ?></title>
        <link rel="stylesheet" href="/pages/register/css/register-building.css">
        <link rel="stylesheet" href="/css/main.css">
        <style>
            body {
                padding: 0px;
            }
        </style>
    </head>
    <body>
        <p id="building_id_p" hidden><?php echo (strval($id)); ?></p>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="back_button_container" class="back_button_container">
            <form action="/pages/info/building_info.php?type=building&id=<?php echo $id; ?>">
                <input type="submit" value="Back">
            </form>
        </div>
        <div id="main_form_container" class="main_form_container">
            <form action="/pages/info/edit/edit_building_submit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo (strval($id)); ?>">
                <div id="essential_container" class="essential_container secondary_container">
                <h3>Essential Details</h3>
                    <div id="coords_container" class="coords_container">
                        <input type="text" id="x_coord" name="x_coord" placeholder="X" class="inline center" value=<?php echo $xcoord; ?>>
                        <input type="text" id="y_coord" name="y_coord" placeholder="Y" class="inline center" value=<?php echo $ycoord; ?>>
                        <button type="button" onclick="get_details()" class="inline center">Generate details</button>
                        <br>
                        <p id="generate_details_error_p"></p>
                    </div>
                    <div id="district_street_unit_container" class="district_street_unit_container">
                        <p id="edit_district_street_unit_info" hidden><?php echo ($parent_district.",".$parent_street_unit); ?></p>
                        <p class="inline">District: </p>
                        <select name="district" id="district_select" onchange="changeStreetUnits()">
                            <?php
                                $stmt = $conn->prepare("SELECT district_name FROM districts");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $stmt->close();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row["district_name"]."'>".$row["district_name"]."</option>";
                                }
                            ?>
                        </select>
                        <p class="inline">Street Unit: </p>
                        <select name="street_unit" id="street_unit_select" class="inline">
                        </select>
                        <p> </p>
                        <p class="inline">Current Postcode Character: </p>
                        <input type="text" name="postcode_char" placeholder="A-9" value="<?php if ($contains_postcode_char) { echo ($postcode_char); } ?>">
                    </div>
                    <div id="building_type_container_main" class="building_type_container_main">
                        <select id="add_building_type">
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="commercial">Commercial</option>
                            <option value="office">Office</option>
                            <option value="industry">Industry</option>
                            <option value="franchise">Franchise</option>
                            <option value="monument">Monument</option>
                            <option value="station">Station</option>
                            <option value="bus_stop">Bus Stop</option>
                            <option value="special">Special</option>
                        </select>
                        <button id="add_building_type_button" class="inline" onclick="addBuildingType()" type="button">Add building type</button>
                        <div id="building_type_list_container">
                            <input type="hidden" name="building_type_list" id="building_type_list_hidden">
                        </div>
                    </div>
                    <div id="street_details_container_main" class="street_details_container_main">
                        <p id="street_details_p">The following details must be enetered exactly, with no spelling mistakes or syntax issues, as it is "unofficial data"</p><br>
                        <p class="inline">Building name or number: </p>
                        <input type="text" name="building_name" placeholder="Flower Cottage / 23" value="<?php echo $building_name; ?>">
                        <br>
                        <p class="inline">Street name: </p>
                        <input type="text" name="street_name" placeholder="London Street" value="<?php if (!$street_name == false) {echo $street_name;} ?>">
                    </div>
                </div>
                <div class="secondary_container" id="construction_container">
                    <h3 id="optional_details_header">Optional Details</h3>
                    <h4>Constuction data</h4>
                    <div id="construction_data">
                        <p>Construction info:</p>
                        <p>Built by: </p><input type="text" name="builders" placeholder="DNAmaster10, Needn_NL" value="<?php if ($has_builders) {echo ($builders);} ?>">
                        <p>Date of completion: </p><input type="date" name="construction_date" value="<?php if ($has_date) {echo $date;} ?>">
                    </div>
                </div>
                <div class="secondary_container" id="cencus_container">
                    <h4>Cencus data</h4>
                    <div id="cencus_data_house" class="inline">
                        <p id="contains_house_data" hidden><?php if ($has_house_data) {echo ("true,".$other_bedrooms_house); } else {echo "false"; }?></p>
                        <h5>House data</h5>
                        <input type="checkbox" id="house_yes_no" name="has_house" value="yes" onclick="show_house()">
                        <label for="house_yes_no">Contains house</label><br>
                        <p>Additional bedrooms: </p><input type="text" name="other_bedrooms_house" id="house_bedroom_ammount" value="0">
                        <button type="button" onclick="increment_house_bedroom_ammount(this)" id="increment_house_bedroom_ammount_1" value="1">+1</button>
                        <button type="button" onclick="increment_house_bedroom_ammount(this)" id="increment_house_bedroom_ammount_-1" value="-1">-1</button>
                    </div>
                    <div id="cencus_data_apartment">
                        <p id="contains_apartment_data" hidden><?php if ($has_apartment_data) { echo ("true,".$other_bedrooms_apartment.",".$furniture_apartment);} else { echo ("false"); }?></p>
                        <h5>Apartment data</h5>
                        <input type="checkbox" id="apartment_yes_no" name="has_apartment" value="yes" onclick="show_apartment()">
                        <label for="apartment_yes_no">Contains apartment</label><br>
                        <p class="inline">Apartments with furniture: </p>
                        <input type="text" name="furniture_ammount" value="0" id="furniture_ammount">
                        <button type="button" onclick="increment_apartment_furniture(this)" id="increment_apartment_furniture_ammount_1" value="1">+1</button>
                        <button type="button" onclick="increment_apartment_furniture(this)" id="increment_apartment_furniture_ammount_-1" value="-1">-1</button>
                        <br>
                        <p class="inline">Additional bedrooms: </p><input type="text" name="other_bedrooms_apartment" id="apartment_bedroom_ammount" value="0">
                        <button type="button" onclick="increment_apartment_bedroom_ammount(this)" id="increment_apartment_bedroom_ammount_1" value="1">+1</button>
                        <button type="button" onclick="increment_apartment_bedroom_ammount(this)" value="-1" id="increment_apartment_bedroom_ammount_-1">-1</button>
                    </div>
                </div>
                <div id="description_container" class="description_container">
                    <h4 id="extra_details_heading">Extra details</h4>
                    <p>Image upload. Files must be either png or jpg / jpeg. Files must not be more than 8MB, as more than this will make loading the image later a slower proccess for people with a slower internet connection. </p>
                    <input type="file" name="image_file" id="image_upload">
                    <br>
                    <h4>Description</h4>
                    <textarea name="description" id="description_text_area" class="description_text_area" rows="5" cols="50"><?php if ($contains_description) { echo ($description); } ?></textarea>
                </div>
                <input type="submit" value="Submit new details" id="form_submit">
                <p><?php if (isset($error)) {
                    echo ($error);
                } ?></p>
            </form>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/info/edit/js/edit_building.js"></script>
    <script src="/pages/info/edit/js/edit_building_type.js"></script>
</html>