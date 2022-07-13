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
    //Finds parent district and street unit chars
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
    //finds available post code char for building
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
        }
        else {
            $loop_count++;
            $current_letter = ++$current_letter;
            unset($result);
        }
    }
    $loop_count = 0;
    $current_letter = "0";
    while ($loop_count != 10 && $empty_found == false) {
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
        }
        else {
            $loop_count++;
            $current_letter = ++$current_letter;
            unset($result);
        }
    }
    if (!$empty_found) {
        $_SESSION["building_error"] = "That street unit is full!";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    //Checks if a building with that name under that street already exists
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
        $_SESSION["building_error"] = "A building with that name on that street already exists.";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    $building_type_list = $conn->real_escape_string($_POST["building_type_list"]);
    $building_type_list_array = explode("#-#",$building_type_list);
    $type_ammount = count($building_type_list_array);
    if ($type_ammount < 1) {
        $_SESSION["buiding_error"] = "Please enter at least one building type";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    if (!isset($_POST[$building_type_list_array[0]."_ammount"])) {
        $_SESSION["building_error"] = "Please enter the ammount of " + $building_type_list_array[0] + "s.";
        header ("Location: /pages/register/register-building.php");
        die();
    }
    //Generates ammount type list
    $ammount_type = $_POST[$building_type_list_array[0]."_ammount"];
    for ($i=1; $i < $type_ammount; $i++) {
        if (!isset($_POST[$building_type_list_array[$i]."_ammount"])) {
            $_SESSION["building_error"] = "Please enter the ammount of " + $building_type_list_array[$i] + "s.";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        $ammount_type = $ammount_type.",".$_POST[$building_type_list_array[$i]."_ammount"];
    }
    $building_type_list = implode(",",$building_type_list_array);

    $builders = $conn->real_escape_string($_POST["builders"]);
    $construction_date = $conn->real_escape_string($_POST["construction_date"]);
    $total_population = 0;
    $total_houses = 0;
    if (isset($_POST["has_house"]) && $_POST["has_house"] == "yes") {
        if (!str_contains($building_type_list, "house")) {
            $_SESSION["building_error"] = "Please add the building type: house, to the list of building types.";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        if (!isset("other_bedrooms_house")) {
            $_SESSION["building_error"] = "Please enter the ammount of additional bedrooms in the house";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        $total_houses = $conn->real_escape_string($_POST["house_ammount"]);
        $total_houses = intval($total_houses);
        $additional_bedrooms = $conn->real_escape_string($_POST["other_bedrooms_house"]);
        $additional_bedrooms = intval($additional_bedrooms);
        $total_population = $total_population + (($total_houses * 2) + $additional_bedrooms);
    }
    if (isset($_POST["has_apartment"]) && $_POST["has_house"] == "yes") {
        if (!str_contains($building_type_list, "apartment")) {
            $_SESSION["building_error"] = "Please add the building type: apartment, to the list of building types.";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        if (!isset("apartment_bedroom_ammount")) {
            $_SESSION["building_error"] = "Please enter the ammount of additional bedrooms in the apartment";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        if (!isset("furniture_ammount")) {
            $_SESSION["building_error"] = "Please enter the ammount of furniture items in the apartment";
            header ("Location: /pages/register/register-building.php");
            die();
        }
        $additional_bedrooms_apartment = $conn->real_escape_string($_POST["apartment_bedroom_ammount"]);
        $addition_bedrooms_apartment = intval($additional_bedrooms_apartment);
        $furniture_ammount = $conn->real_escape_string($_POST["furniture_ammount"]);
        $furniture_ammount = intval($furniture_ammount);
        $total_apartments = $conn->real_escape_string($_POST["apartment_ammount"]);
        $total_apartments = intval($total_apartments);
        $apartments_without_furniture = $total_apartments - $furniture_ammount;
        $total_population = $total_population + ($apartments_without_furniture + $furniture_ammount + $addition_bedrooms_apartment);
    }
    if (!isset($_POST["description"])) {
        $description = "none";
    }
    else {
        $description = $conn->real_escape_string($_POST["description"]);
    }
?>






























