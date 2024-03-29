<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (isset($_SESSION["building_error"])) {
        $error = $_SESSION["building_error"];
        unset($_SESSION["building_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register Building</title>
    </head>
    <link rel="stylesheet" href="./css/register-building.css">
    <link rel="stylesheet" href="/css/main.css">
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <div id="back_button_container" class="back_button_container">
                <form action="/pages/register/building-home.php">
                    <input type="submit" value="Back">
                </form>
            </div>
            <div id="main_form_container" class="main_form_container">
                <form action="/pages/register/register-building_submit.php" method="POST" enctype="multipart/form-data">
                    <div id="essential_container" class="essential_container secondary_container">
                    <h3>Essential Details</h3>
                        <div id="coords_container" class="coords_container">
                            <input type="text" id="x_coord" name="x_coord" placeholder="X" class="inline center">
                            <input type="text" id="y_coord" name="y_coord" placeholder="Y" class="inline center">
                            <button type="button" onclick="get_details()" class="inline center">Generate details</button>
                            <br>
                            <p id="generate_details_error_p"></p>
                        </div>
                        <div id="district_street_unit_container" class="district_street_unit_container">
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
                            <select name="street_unit" id="street_unit_select">
                            </select>
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
                            <input type="text" name="building_name" placeholder="Flower Cottage / 23">
                            <br>
                            <p class="inline">Street name: </p>
                            <input type="text" name="street_name" placeholder="London Street">
                        </div>
                    </div>
                    <div class="secondary_container" id="construction_container">
                        <h3 id="optional_details_header">Optional Details</h3>
                        <h4>Constuction data</h4>
                        <div id="construction_data">
                            <p>Construction info:</p>
                            <p>Built by: </p><input type="text" name="builders" placeholder="DNAmaster10, Needn_NL">
                            <p>Data of completion: </p><input type="date" name="construction_date">
                        </div>
                    </div>
                    <div class="secondary_container" id="cencus_container">
                        <h4>Cencus data</h4>
                        <div id="cencus_data_house" class="inline">
                            <h5>House data</h5>
                            <input type="checkbox" id="house_yes_no" name="has_house" value="yes" onclick="show_house()">
                            <label for="house_yes_no">Contains house</label><br>
                            <p>Additional bedrooms: </p><input type="text" name="other_bedrooms_house" id="house_bedroom_ammount" value="0">
                            <button type="button" onclick="increment_house_bedroom_ammount(this)" id="increment_house_bedroom_ammount_1" value="1">+1</button>
                            <button type="button" onclick="increment_house_bedroom_ammount(this)" id="increment_house_bedroom_ammount_-1" value="-1">-1</button>
                        </div>
                        <div id="cencus_data_apartment">
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
                        <textarea name="description" id="description_text_area" class="description_text_area" rows="5" cols="50"></textarea>
                    </div>
                    <input type="submit" value="Register Building" id="form_submit">
                    <p><?php if (isset($error)) {
                        echo ($error);
                    } ?></p>
                </form>
            </div>
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/register/register-building.js"></script>
    <script src="register-building-type.js"></script>
</html>
