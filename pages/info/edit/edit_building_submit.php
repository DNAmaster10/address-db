<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    function error($error_message) {
        $_SESSION["building_error"] = $error_message;
        header ("Location: /pages/info/edit/edit_building.php");
        die();
    }

    //Check if all essential types are set
    if (!isset($_POST["id"])) {
        error("No ID was set");
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
    
    //Check if a char is set already
    if (isset($_POST["postcode_char"])) {
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
    //Check if it's a duplicate building name
    $building_name = $_POST["building_name"];
    $street_name = $_POST["street_name"];

    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE parent_street=? AND building_name=? AND id!=?");
    $stmt->bind_param("ssi", $street_name,$building_name,$id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
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
    $ammount_type = $_POST[$buil]
?>