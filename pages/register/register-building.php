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
    <style>
        .commerce_type_container {
            visibility: hidden;
        }
        .building_type_container {
            border: 1px solid black;
        }
        .inline {
            display: inline;
        }
    </style>
    <body>
        <form action="/pages/register/building-home.php">
            <input type="submit" value="Back">
        </form>
        <form action="register-building_submit.php" method="POST" name="mainForm">
            <p>Co-ords. Format: "x,y" e.g: "10,-23". Pressing "Generate details" will attempt to autofill all inputs that can be found based on the buildings location.</p>
            <input type="text" name="coord" placeholder="x,y" id="coords" required>
            <button type="button" onclick="get_details()">Generate details</button>
            <p>District: </p>
            <select name="district" id="district_select" onchange='changeStreetUnits()' required>
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
            <p>Street Unit: </p>
            <select name="street_unit" id="street_unit_select" required>
            </select>
            <p>Building type(s): </p>
            <div id="all_building_type_container">
                <div id="building_type_list_container">
                    <input type="hidden" name="building_types" id="building_types" value="">
                </div>
                <div id="commerce_type" class="commerce_type_container">
                    <input type="hidden" name="commerce_items" id="commerce_items_hidden" value="">
                    <p id="commerce_p"></p>
                    <input type="text" placeholder="Movies / food e.t.c" id="commerce_type_text_input"></input>
                    <button onclick="add_commerce_item()">Add item</button>
                </div>
                <div id="building_type_select_container">
                    <select id="add_building_type" onchance="add_type()">
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
                    <button id="add_building_type_button" onclick="addBuildingType()">Add type</button>
                </div>
            </div>
            <p>Street name. This entry box is unnofficial. Please enter the street name in exact syntax:</p>
            <input type="text" name="street" placeholder="Highbroom avenue" required>
        </form>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/register/register-building.js"></script>
</html>
