<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    //Checks if all essential types are set
    if (!isset($_POST["x_coord"])) {
        $_SESSION["building_error"] = "Please enter a valid x co-ordinate.";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST["y_coord"])) {
        $_SESSION["building_error"] = "Please enter a valid y co-odinate.";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST["district"])) {
        $_SESSION["building_error"] = "Please enter a valid district name";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST["street_unit"])) {
        $_SESSION["building_error"] = "Please enter a valid street unit name";
        header ("Location: /pages/regsiter/register-building.php");
        die();
    }
    if (!isset($_POST["building_type_list"])) {
        $_SESSION["building_error"] = "Please enter at least one building type for this building";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST["building_name"])) {
        $_SESSION["building_error"] = "Please enter a valid name for this building.";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST["street_name"])) {
        $_SESSION["building_error"] = "Please enter a valid street name for this building";
        header ("Location: /pages/register/register-building.php");
        die();
    }

    $district_name = $conn->real_escape_string($_POST["district"]);
    $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE district_name =?");
    $stmt->bind_param("s", $district_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["building_error"] = "Please enter a valid district name";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    $district_char = $result;

    $street_unit_name = $conn->real_escape_string($_POST["street_unit"]);
    $stmt = $conn->prepare("SELECT postcodeChar FROM street_units WHERE name=?");
    $stmt->bind_param("s", $street_unit_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["building_error"] = "Please enter a valid street unit name";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    $street_unit_char = $result;

    $current_letter = "A";
    $loop_count = 1;
    $empty_found = false;
    $postcode_pre = $district_char.$street_unit_char;
    while ($loop_count !=27 && $empty_found == false) {
        $current_postcode = $postcode_pre.$current_letter;
        $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE postcode=?");
        $stmt->bind_param("s",$current_postcode);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (!$result) {
            unset ($result);
            $empty_found = true;
            $free_char = $current_letter;
            error_log("Empty found!");
        }
        else {
            $loop_count++;
            $current_letter = ++$current_letter;
            unset($result);
        }
    }
    $loop_count = 0;
    $current_letter = "0";
    while ($loop_count != 10 && $empty_found = false) {
        $current_postcode = $postcode_pre.$current_letter;
        $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE postcode=?");
        $stmt->bind_param("s",$current_postcode);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (!$result) {
            unset ($result);
            $empty_found = true;
            $free_char = $current_letter;
            error_log("Empty found!");
        }
        else {
            $loop_count++;
            $current_letter = ++$current_letter;
            unset($result);
        }
    }
    error_log($empty_found);
    if (!$empty_found) {
        $_SESSION["building_error"] = "That street unit is full!";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    $building_name = $conn->real_escape_string($_POST["building_name"]);
    $street_name = $conn->real_escape_string($_POST["street_name"]);

    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE parent_street=?");
    $stmt->bind_param("s",$street_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        unset($result);
    }
    else {
        $_SESSION["building_error"] = "A building with that name on that street already exists";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    $building_type_list = $conn->real_escape_string($_POST["building_type_list"]);
    $building_type_list_array = explode("#-#",$building_type_list);
    $type_ammount = count($building_type_list_array);
    for ($i=0; $i < $type_ammount; $i++) {
        error_log($building_type_list_array[i].": ".$_POST[$building_type_list_array[i]."ammount"]);
    }
?>






























