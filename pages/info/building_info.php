<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";
    //Make sure variables for searching are set
    if (!isset($_POST["id"])) {
        $_SESSION["generic_error"] = "POST variable 'id' is not set";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    //Check to make sure building exists in database
    $building_id = $conn->real_escape_string($_POST["id"]);
    $building_id = intval($building_id);
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic_error"] = "Building not found in database";
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
    //Grab district
    $stmt = $conn->prepare("SELECT parent_district FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $district = "none";
    }
    else {
        $district = $result;
    }
    unset($result);
    //Grab street unit
    $stmt = $conn->prepare("SELECT parent_street_unit FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street_unit = "none";
    }
    else {
        $street_unit = $result;
    }
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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Building info</title>
    </head> 
    <body>
        <div id="main_container">
            <h2><?php echo $building_name; ?></h2>
            <div id="side_box">
                <p class="info_text">Postcode: <?php echo($postcode); ?></p>
                <p class="info_text">District: <?php echo($district); ?></p>
                <p class="info_text">Street Unit: <?php echo($street_unit); ?></p>
                <p class="info_text">Street: <?php echo($street); ?></p>
            </div>
        </div>
    </body>
</html>