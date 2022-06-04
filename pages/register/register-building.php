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
    <body>
        <div id="back_button_container" class="back_button_container">
            <form action="/pages/register/building-home.php">
                <input type="submit" value="Back">
            </form>
        </div>
        <div id="main_form_container" class="main_form_container">
            <form action="/pages/register/register-building_submit.php" method="POST">
                <div id="essential_container" class="essential_container secondary_container">
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
                    <div id="building_type_container">
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
                </div>
                <input type="submit" value="Register Building">
            </form>
        </div>
        <script src="register-building-type.js"></script>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/register/register-building.js"></script>
</html>
