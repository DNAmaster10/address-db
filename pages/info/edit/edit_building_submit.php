<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    function error($error_message) {
        //Get ID
        $id = $_POST["id"];
        $_SESSION["building_error"] = $error_message;
        header ("Location: /pages/info/edit/edit_building.php?"."id=".strval($id));
        die();
    }

    //Check if all essential types are set
    if (!isset($_POST["id"])) {
        $_SESSION["generic_error"] = "No building ID was set in the GET request";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    if (!isset($_POST["x_coord"])) {
        error("Please enter a valid x co-ord");
    }
    if (!isset($_POST["y_coord"])) {
        error("Please enter a valid y co-ord");
    }
    if (!isset($_POST["district"])) {
        error("Please enter a valid district");
    }
    if (!isset($_POST["street_unit"])) {
        error("Please enter a valid street unit");
    }
    if (!isset($_POST["building_type_list"])) {
        error("Please enter at least one building type");
    }
    if (!isset($_POST["building_name"])) {
        error("Please enter a valid building name");
    }
    if (!isset($_POST["street_name"])) {
        error("Please enter a valid street name");
    }

    $id = $_POST["id"];

    //Find street unit char
    $street_unit_name = $_POST["street_unit"];
    $stmt = $conn->prepare("SELECT postcodeChar FROM street_units WHERE name=?");
    $stmt->bind_param("s", $street_unit_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        error("Street unit name invalid");
    }
    else {
        $street_unit_char = $result;
    }
    unset($result);
    
    //Check if a char is set already
    if (isset($_POST["postcode_char"])) {
        $postcode_char = strtoupper($_POST["postcode_char"]);
        //Make sure that the char isn't a duplicate
        $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE postcode_char=? AND id!=?");
        $stmt->bind_param("si", $_POST["postcode_char"],$id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if ($result) {
            error("A building with that postcode char in that street unit already exists");
        }

        //Validate postcode char if it's not duplicate
        //make sure it's one character only
        if (strlen($postcode_char) > 1) {
            error("Postcode entered is too long");
        }
        //Make sure it's a letter or a number
        $is_letter_or_num = false;
        if (preg_match('/\d/', $postcode_char)) {
            $is_letter_or_num = true;
        }
        if (preg_match('/[a-zA-Z]/', $postcode_char)){
            $is_letter_or_num = true;
        }
        if (!$is_letter_or_num) {
            error("Postcode must be a letter or a number");
        }
    }

    //Find district char
    $district_name = $_POST["district"];
    $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE district_name = ?");
    $stmt->bind_param("s", $district_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        error("Please enter a valid district name");
    }
    else {
        $district_char = $result;
    }

    //If a postcode char isn't entered, find a new one
    if (!isset($_POST["postcode_char"])) {
        $current_letter = "A";
        $loop_count = 1;
        $empty_found = false;
        $postcode_pre = $district_char.$street_unit_char;
        while ($loop_count !=27 && $empty_found == false) {
            $current_postcode = $postcode_pre.$current_letter;
            $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE postcode=? AND id!=?");
            $stmt->bind_param("si",$current_postcode,$id);
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
        $current_letter = 0;
        while ($loop_count != 10 && $empty_found == false) {
            $current_postcode = $postcode_pre.strval($current_letter);
            $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE postcode=? AND id!=?");
            $stmt->bind_param("si",$current_postcode, $id);
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
                $current_letter = $current_letter + 1;
                unset($result);
            }
        }
        if (!$empty_found) {
            error("That street unit is full!");
        }
        unset ($result);
        $postcode = $postcode_pre.$free_char;
        $postcode_char = $free_char;
    }
    else {
        $postcode = $district_char.$street_unit_char.$postcode_char;
    }
    //Check if it's a duplicate building name
    $building_name = $_POST["building_name"];
    $street_name = $_POST["street_name"];

    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE parent_street=? AND building_name=? AND id!=?");
    $stmt->bind_param("ssi", $street_name,$building_name,$id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    error_log($result." Is culpret");
    if ($result) {
        error("A building with that name under that street already exists!");
    }
    unset ($result);

    //Add street to street table if it doesn't exist
    $stmt = $conn->prepare("SELECT street FROM streets WHERE street=?");
    $stmt->bind_param("s", $street_name);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $stmt = $conn->prepare("INSERT INTO streets (street) VALUES (?)");
        $stmt->bind_param("s", $street_name);
        $stmt->execute();
        $stmt->close();
    }
    unset ($result);

    //Get x and y
    $x_coord = $_POST["x_coord"];
    $y_coord = $_POST["y_coord"];
    if (!is_numeric($x_coord) || !is_numeric($y_coord)) {
        error("Coords invalid");
    }
    $x_coord = intval($x_coord);
    $y_coord = intval($y_coord);

    //Generate new ammount type list
    $building_type_list = $_POST["building_type_list"];
    $building_type_list_array = explode("#-#", $building_type_list);
    $type_ammount = count($building_type_list_array);
    if ($type_ammount < 1) {
        error("Please enter at least one building type");
    }
    if (!isset($_POST[$building_type_list_array[0]."_ammount"])) {
        error("Please enter the ammount of " + $building_type_list_array[0]);
    }
    $ammount_type = $_POST[$building_type_list_array[0]."_ammount"];
    for ($i = 1; $i < $type_ammount; $i++) {
        if (!isset($_POST[$building_type_list_array[$i]."_ammount"])) {
            error("Please enter the ammount of " + $building_type_list_array[0]);
        }
        $ammount_type = $ammount_type.",".$_POST[$building_type_list_array[$i]."_ammount"];
    }
    $building_type_list = implode(",",$building_type_list_array);


    //Check commercial info
    if (str_contains($building_type_list, "commercial")) {
        if (!isset($_POST["commerce_types"])) {
            error("Please specify the commerce type in the commercial building");
        }
        $commerce_types = $_POST["commerce_types"];
    }
    //Check franchise info
    if (str_contains($building_type_list, "franchise")) {
        if (!isset($_POST["commerce_types_franchise"])) {
            error("Please specify the commerce type of the franchise");
        }
        if (isset($commerce_types)) {
            $temp_types = $_POST["commerce_types_franchise"];
            $commerce_types = $commerce_types."-@-".$temp_types;
        }
        else {
            $commerce_types = $_POST["commerce_types_franchise"];
        }
        if (!isset($_POST["franchise_owners"])) {
            error("Please specify the franchise owners");
        }
        else {
            $franchise_owners = $_POST["franchise_owners"];
        }
    }
    //Check builders
    if (isset($_POST["builders"])) {
        $builders = $_POST["builders"];
    }
    //Check construction date
    if (isset($_POST["construction_date"])) {
        $construction_date = $_POST["construction_date"];
    }

    //Check cencus data house
    if (isset($_POST["has_house"]) && $_POST["has_house"] == "yes") {
        $has_house = true;
        if (!str_contains($building_type_list, "house")) {
            error("Please add the building type 'house' before registering cencus data for a house");
        }
        if (!isset($_POST["other_bedrooms_house"])) {
            error("Please enter the ammount of additional bedrooms in the house");
        }
    }
    else {
        $has_house = false;
    }
    //Check apartment cencus data
    if (isset($_POST["has_apartment"]) && $_POST["has_apartment"] == "yes") {
        $has_apartment = true;
        if (!str_contains($building_type_list, "apartment")) {
            error("Please add the building type 'apartment' before registering apartment cencus data");
        }
        if (!isset($_POST["other_bedrooms_apartment"])) {
            error("Please enter all cencus data for the apartment");
        }
        if (!isset($_POST["furniture_ammount"])) {
            error("Please enter all cencus data for the apartment");
        }
    }
    else {
        $has_apartment = false;
    }

    //Calculate population based on cencus data
    if (!$has_apartment && !$has_house) {
        $population = 0;
    }
    else if (!$has_apartment && $has_house) {
        $total_houses = intval($_POST["house_ammount"]);
        $other_bedrooms_house = intval($_POST["other_bedrooms_house"]);
        $population = ($total_houses * 2) + $other_bedrooms_house;
    }
    else if ($has_apartment && !$has_house) {
        $total_apartments = intval($_POST["apartment_ammount"]);
        $total_additional_bedrooms_apartment = intval($_POST["other_bedrooms_apartment"]);
        $total_furniture_apartment = intval($_POST["furniture_ammount"]);
        $population = ((($total_apartments - ($total_apartments - $total_furniture_apartment)) * 2) + ($total_apartments - $total_furniture_apartment) + $total_additional_bedrooms_apartment);
    }
    else {
        $house_population = ($total_houses * 2) + $other_bedrooms_house;
        $apartment_population = ((($total_apartments - ($total_apartments - $total_furniture_apartment)) * 2) + ($total_apartments - $total_furniture_apartment) + $total_additional_bedrooms_apartment);
        $population = $house_population + $apartment_population;
    }

    //Check description
    if (isset($_POST["description"])) {
        $description = $_POST["description"];
    }

    //Update database code

    //Update street unit, district, postcode and coords, and street name and building name
    $stmt = $conn->prepare("UPDATE buildings SET parent_street_unit=?,parent_district=?,postcode=?,postcode_char=?,parent_street=?,building_name=? WHERE id=?");
    $stmt->bind_param("ssssi",$street_unit_name,$district_name,$postcode,$postcode_char,$street_name,$building_name,$id);
    $stmt->execute();
    $stmt->close();

    //Update type array
    $stmt = $conn->prepare("UPDATE buildings SET tpyes=?,type_ammount=? WHERE id=?");
    $stmt->bind_param("ssi",$building_type_list,$ammount_type,$id);
    $stmt->execute();
    $stmt->close();

    //Update franchise owners
    if (isset($franchise_owners)) {
        $stmt = $conn->prepare("UPDATE buildings SET franchise_owners=? WHERE id=?");
        $stmt->bind_param("si", $franchise_owners,$id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET franchise_owners=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    //Update commerce types
    if (isset($commerce_types)) {
        $stmt = $conn->prepare("UPDATE buildings SET commerce_types=? WHERE id=?");
        $stmt->bind_param("si",$commerce_types,$id);
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET commerce_types=NULL WHER id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $stmt->close();
    }

    //Update cencus info
    if ($has_house) {
        $stmt = $conn->prepare("UPDATE buildings SET contains_house='yes',other_bedrooms_house=? WHERE id=?");
        $stmt->bind_param("ii", $other_bedrooms_house, $id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET contains_house='no',other_bedrooms_house=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    if ($has_apartment) {
        $stmt = $conn->prepare("UPDATE buildings SET contains_apartment='yes',furniture_apartment=?,other_bedrooms_apartment=? WHERE id=?");
        $stmt->bind_param("iii", $total_furniture_apartment,$total_additional_bedrooms_apartment,$id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET contains_apartment='no',furniture_apartment=NULL,other_bedrooms_apartment=? WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    if ($has_house || $has_apartment) {
        $stmt = $conn->prepare("UPDATE buildings SET population=? WHERE id=?");
        $stmt->bind_param("ii", $population,$id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET population=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    //Update construction details 
    if (isset($builders)) {
        $stmt = $conn->prepare("UPDATE buildings SET builders=? WHERE id=?");
        $stmt->bind_param("si", $builders, $id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET builders=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
    }
    
    if (isset($construction_date)) {
        $stmt = $conn->prepare("UPDATE buildings SET construction_date=? WHERE id=?");
        $stmt->bind_param("si", $construction_date, $id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET construction_date=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    //Set description
    if (isset($description)) {
        $stmt = $conn->prepare("UPDATE buildings SET description=? WHERE id=?");
        $stmt->bind_param("si", $description,$id);
        $stmt->execute();
        $stmt->close();
    }
    else {
        $stmt = $conn->prepare("UPDATE buildings SET description=NULL WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: /pages/info/building_info.php?type=building&id=".strval($id));
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/main.css"></link>
    </head>
    <body>
        <?php include $_SERVER("DOCUMENT_ROOT")."/pages/includes/html/header.php"; ?>
        <p>If you are seeing this page, an error has occured. Please contact DNAmaster10 for further support</p>
    </body>
</html>