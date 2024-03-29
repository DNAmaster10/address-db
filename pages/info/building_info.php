<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";
    //Make sure variables for searching are set
    if (!isset($_GET["id"])) {
        $_SESSION["generic_error"] = "GET variable 'id' is not set";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    if (!is_numeric($_GET["id"])) {
        $_SESSION["generic_error"] = "Id invalid";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    //Check to make sure building exists in database
    $building_id = $_GET["id"];
    $building_id = intval($building_id);
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic-error"] = "Building not found in database";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    else {
        $building_name = $result;
    }
    unset($result);
    //Grab data from database
    //Grab postcode
    $stmt = $conn->prepare("SELECT postcode FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $postcode = "none";
    }
    else {
        $postcode = $result;
    }
    unset($result);
    //Grab district char
    $stmt = $conn->prepare("SELECT parent_district FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $district_char = "none";
    }
    else {
        $district_char = $result;
    }
    unset($result);

    //Grab district name
    $stmt = $conn->prepare("SELECT district_name FROM districts WHERE postcodeChar=?");
    $stmt->bind_param("s", $district_char);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    $district = $result;
    unset ($result);

    //Grab street unit char
    $stmt = $conn->prepare("SELECT parent_street_unit FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street_unit_char = "none";
    }
    else {
        $street_unit_char = $result;
    }
    unset($result);
    //Grab street unit
    $stmt = $conn->prepare("SELECT name FROM street_units WHERE postcodeChar=?");
    $stmt->bind_param("s", $street_unit_char);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    $street_unit = $result;
    unset($result);
    
    //Grab street
    $stmt = $conn->prepare("SELECT parent_street FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street = "none";
    }
    else {
        $street = $result;
    }
    unset($result);
    //Grab description
    $stmt = $conn->prepare("SELECT description FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $description = "none";
    }
    else {
        $description = $result;
    }
    unset($result);
    //Grab population
    $stmt = $conn->prepare("SELECT population FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $population = "0";
    }
    else {
        $population = $result;
    }
    unset($result);
    //Grab construction date
    $stmt = $conn->prepare("SELECT construction_date FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $construction_date = "Unknown";
    }
    else {
        $construction_date = $result;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo($building_name); ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/building_info.css">
    </head> 
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <h2><?php echo $building_name; ?></h2>
            <div id="side_box">
                <p class="info_text">Postcode: <?php echo($postcode); ?></p>
                <p class="info_text">District: <?php echo($district); ?></p>
                <p class="info_text">Street Unit: <?php echo($street_unit); ?></p>
                <p class="info_text">Street: <?php echo($street); ?></p>
                <p class="info_text">Population: <?php echo($population); ?></p>
                <p class="info_text">Construction Date: <?php echo($construction_date); ?></p>
                <p class="info_text">Description: <?php echo($description); ?></p>
                <?php if($logged_in) {
                    $building_id_string = strval($building_id);
                    echo ("<form id='edit_button_form' action='/pages/info/edit/edit_building.php' method='GET'><input type='hidden' value='$building_id_string' name='id'><input id='edit_button' type='submit' value='Edit'></form>");
                } ?>
            </div>
            <div id="inner_building_container">
            </div>
        </div>
        <p hidden id="building_id_p"><?php echo($building_id); ?></p>
    </body>
    <script src="/pages/info/js/building_info.js"></script>
</html>