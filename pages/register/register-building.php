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
        .commerce_type {
            visibility: hidden;
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
            <p>Building type: </p>
            <div id="building_type_select">
                <input type="radio" id="houseSelect" name="housetype" onclick="typeChange(this)" value="house">
                <label for="houseSelect">House</label>
                <input type="radio" id="apartmentSelect" name="apartmenttype" onclick="typeChange(this)" value="apartment">
                <label for="apartmentSelect">Apartment</label>
                <input type="radio" id="commercialSelect" name="commercialtype" onclick="typeChange(this)" value="commercial">
                <label for="commercialSelect">Commercial</label>
                <input type="radio" id="officeSelect" name="officetype" onclick="typeChange(this)" value="office">
                <label for="officeSelect">Office</label>
                <input type="radio" id="industrySelect" name="industrytype" onclick="typeChange(this)" value="industry">
                <label for="industrySelect">Industry</label>
                <input type="radio" id="franchiseSelect" name="franchisetype" onclick="typeChange(this)" value="franchise">
                <label for="franchiseSelect">Franchise</label>
                <input type="radio" id="monumentSelect" name="monumenttype" onclick="typeChange(this)" value="monument">
                <label for="monumentSelect">Monument</label>
                <input type="radio" id="stationSelect" name="stationtype" onclick="typeChange(this)" value="station">
                <label for="stationSelect">Station</label>
                <input type="radio" id="bus_stopSelect" name="bus_stoptype" onclick="typeChange(this)" value="bus_stop">
                <label for="bus_stopSelect">Bus stop</label>
                <input type="radio" id="specialSelect" name="specialtype" onclick="typeChange(this)" value="special">
                <label for="specialSelect">Special</label>
            </div>
            <div id="commerce_type" class="commerce_type">

            </div>
            <p>Street name. This entry box is unnofficial. Please enter the street name in exact syntax:</p>
            <input type="text" name="street" placeholder="Highbroom avenue" required>
        </form>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/register/register-building.js"></script>
</html>
