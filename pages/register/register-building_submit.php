<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    function removeRow() {
        $stmt = $conn->prepare("DELETE FROM buildings WHERE postcode = ?");
        $stmt->bind_param("s", $postcode);
        $stmt->close;
        header ("Location: /pages/register/register-building.php");
        die();
    }

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
    $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE district_name = ?");
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
    $postcode = $postcode_pre.$free_char;
    $postcode_char = $free_char;
    //Checks if a building with that name under that street already exists
    $building_name = $conn->real_escape_string($_POST["building_name"]);
    $street_name = $conn->real_escape_string($_POST["street_name"]);

    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE parent_street=? AND building_name=?");
    $stmt->bind_param("ss",$street_name, $building_name);
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
    //Get x and y co-ords
    $x_coord = $conn->real_escape_string($_POST["x_coord"]);
    $y_coord = $conn->real_escape_string($_POST["y_coord"]);
    $x_coord = intval($x_coord);
    $y_coord = intval($y_coord);

    //Insert the current details into the database
    $stmt = $conn->prepare("INSERT INTO buildings (parent_street_unit, parent_district, postcode, postcode_char, parent_street, building_name, x, y) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $street_unit_char, $district_char, $postcode, $postcode_char, $street_name, $building_name, $x_coord, $y_coord);
    $stmt->execute();
    $stmt->close();

    $building_type_list = $conn->real_escape_string($_POST["building_type_list"]);
    $building_type_list_array = explode("#-#",$building_type_list);
    $type_ammount = count($building_type_list_array);
    if ($type_ammount < 1) {
        $_SESSION["buiding_error"] = "Please enter at least one building type";
        header ("Location: /pages/register/register-building.php");
        removeRow();
    }
    if (!isset($_POST[$building_type_list_array[0]."_ammount"])) {
        $_SESSION["building_error"] = "Please enter the ammount of " + $building_type_list_array[0] + "s.";
        removeRow();
    }
    //Generates ammount type list
    $ammount_type = $_POST[$building_type_list_array[0]."_ammount"];
    for ($i=1; $i < $type_ammount; $i++) {
        if (!isset($_POST[$building_type_list_array[$i]."_ammount"])) {
            $_SESSION["building_error"] = "Please enter the ammount of " + $building_type_list_array[$i] + "s.";
            removeRow();
        }
        $ammount_type = $ammount_type.",".$_POST[$building_type_list_array[$i]."_ammount"];
    }
    $building_type_list = implode(",",$building_type_list_array);

    $stmt = $conn->prepare("UPDATE buildings SET types = ?, type_ammount = ? WHERE postcode = ?");
    $stmt->bind_param("sss", $building_type_list, $ammount_type, $postcode);
    $stmt->execute();
    $stmt->close();


    if (str_contains($building_type_list, "commercial")) {
        if (!isset($_POST["commerce_types"])) {
            $_SESSION["building_error"] = "Please specify the commerce types sold in the commercial building(s)";
            removeRow();
        }
        $commerce_types = $conn->real_escape_string($_POST["commerce_types"]);
    }
    if (str_contains($building_type_list, "franchise")) {
        if (!isset($_POST["commerce_types_franchise"])) {
            $_SESSION["building_error"] = "Please specify the commerce types sold in the franchise building(s)";
            removeRow();
        }
        if (isset($commerce_types)) {
            $temp_types = $conn->real_escape_string($_POST["commerce_types_franchise"]);
            $commerce_types = $commerce_types.",".$temp_types;
        }
        else {
            $commerce_types = $conn->real_escape_string($_POST["commerce_types_franchise"]);
        }
        if (!isset($_POST["franchise_owners"])) {
            $_SESSION["building_error"] = "Please specify the owners of the franchises contained in the building";
            removeRow();
        }
        else {
            $franchise_owners = $conn->real_escape_string($_POST["franchise_owners"]);
            $stmt = $conn->prepare("UPDATE buildings SET franchise_owners = ? WHERE postcode=?");
            $stmt->bind_param("ss", $franchise_owners, $postcode);
            $stmt->execute();
            $stmt->close();
        }
    }
    if (isset($commerce_types)) {
        $stmt = $conn->prepare("UPDATE buildings SET commerce_types = ? WHERE postcode=?");
        $stmt->bind_param("ss", $commerce_types, $postcode);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST["builders"])) {
        $builders = $conn->real_escape_string($_POST["builders"]);
        $stmt = $conn->prepare("UPDATE buildings SET builders = ? WHERE postcode=?");
        $stmt->bind_param("ss", $builders, $postcode);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_POST["construction_date"])) {
        $construction_date = $conn->real_escape_string($_POST["construction_date"]);
        $stmt = $conn->prepare("UPDATE buildings SET construction_date = ? WHERE postcode=?");
        $stmt->bind_param("ss", $construction_date, $postcode);
        $stmt->execute();
        $stmt->close();
    }
    //Calculate house population
    $total_population = 0;
    $total_houses = 0;
    if (isset($_POST["has_house"]) && $_POST["has_house"] == "yes") {
        if (!str_contains($building_type_list, "house")) {
            $_SESSION["building_error"] = "Please add the building type: house, to the list of building types.";
            removeRow();
        }
        $param = "yes";
        $stmt = $conn->prepare("UPDATE buildings SET contains_house = ? WHERE postcode=?");
        $stmt->bind_param("ss", $param, $postcode);
        $stmt->execute();
        $stmt->close();
        unset($param);

        $total_houses = $conn->real_escape_string($_POST["house_ammount"]);
        $total_houses = intval($total_houses);
        if (!isset($_POST["other_bedrooms_house"])) {
            $_SESSION["building_error"] = "Please enter the ammount of additional bedrooms contained in every house present other than the master bedroom";
            removeRow();
        }
        $additional_bedrooms = $conn->real_escape_string($_POST["other_bedrooms_house"]);
        $additional_bedrooms = intval($additional_bedrooms);

        $stmt = $conn->prepare("UPDATE buildings SET other_bedrooms_house = ? WHERE postcode = ?");
        $stmt->bind_param("is", $additional_bedrooms, $postcode);
        $stmt->execute();
        $stmt->close();

        $total_population = $total_population + (($total_houses * 2) + $additional_bedrooms);
    }
    else {
        //Set contains house to no
        $param = "no";
        $stmt = $conn->prepare("UPDATE buildings SET contains_house = ? WHERE postcode=?");
        $stmt->bind_param("ss", $param, $postcode);
        $stmt->execute();
        $stmt->close();
        unset($param);
    }
    //Caclulate apartment population
    if (isset($_POST["has_apartment"]) && $_POST["has_apartment"] == "yes") {
        if (!str_contains($building_type_list, "apartment")) {
            $_SESSION["building_error"] = "Please add the building type: apartment, to the list of building types.";
            removeRow();
        }
        if (!isset($_POST["apartment_bedroom_ammount"])) {
            $_SESSION["building_error"] = "Please enter the ammount of additional bedrooms in the apartment";
            removeRow();
        }
        if (!isset($_POST["furniture_ammount"])) {
            $_SESSION["building_error"] = "Please enter the ammount of furniture items in the apartment";
            removeRow();
        }
        $additional_bedrooms_apartment = $conn->real_escape_string($_POST["apartment_bedroom_ammount"]);
        $additional_bedrooms_apartment = intval($additional_bedrooms_apartment);
        $furniture_ammount = $conn->real_escape_string($_POST["furniture_ammount"]);
        $furniture_ammount = intval($furniture_ammount);
        $total_apartments = $conn->real_escape_string($_POST["apartment_ammount"]);
        $total_apartments = intval($total_apartments);
        $apartments_without_furniture = $total_apartments - $furniture_ammount;
        $total_population = $total_population + ($apartments_without_furniture + $furniture_ammount + $addition_bedrooms_apartment);

        $param = "yes";
        $stmt = $conn->prepare("UPDATE buildings SET furniture_apartment = ?, other_bedrooms_apartment = ?, contains_apartment = ? WHERE postcode=?");
        $stmt->bind_param("iiss",$furniture_ammount,$additional_bedrooms_apartment, $param, $postcode);
        $stmt->execute();
        $stmt->close();
        unset($param);
    }
    else {
        $param = "no";
        $stmt = $conn->prepare("UPDATE buildings SET contains_apartment = ? WHERE postcode = ?");
        $stmt->bind_param("ss", $param, $postcode);
        $stmt->execute();
        $stmt->close();
        unset($param);
    }
    $stmt = $conn->prepare("UPDATE buildings SET population = ? WHERE postcode = ?");
    $stmt->bind_param("is", $total_population, $postocde);
    $stmt->execute();
    $stmt->close();

    if (!isset($_POST["description"])) {
        $description = "none";
    }
    else {
        $description = $conn->real_escape_string($_POST["description"]);
    }
    $stmt = $conn->prepare("UPDATE buildings SET description = ? WHERE postcode=?");
    $stmt->bind_param("ss",$description,$postcode);
    $stmt->execute();
    $stmt->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registered!</title>
    </head>
    <body>
        <p>The building has been registered</p>
    </body>
</html>
