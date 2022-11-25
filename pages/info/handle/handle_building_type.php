<?php
    include $_SERVER["DOCUMENT_ROOT"].("/includes/dbh.php");
    $contains_types = false;
    //uses $_POST["building_id"]. Requires database connection.
    if (!isset($_POST["building_id"])) {
        echo ("Error: Id not set");
        die();
    }
    if (!is_numeric($_POST["building_id"])) {
        echo ("Error: Id not an integer");
        die();
    }
    $building_id = intval($_POST["building_id"]);

    //Check ID exists in database
    $stmt = $conn->prepare("SELECT id FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        echo ("Error: Id not in database");
        die();
    }

    //get building types array
    $stmt = $conn->prepare("SELECT types FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if ($result) {
        $contains_types = true;
        $types = $result;
        unset ($result);
    }
    if ($contains_types) {
        $stmt = $conn->prepare("SELECT type_ammount FROM buildings WHERE id=?");
        $stmt->bind_param("i", $building_id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (!$result) {
            $contains_types = false;
        }
        else {
            $types_ammount = $result;
            unset ($result);
        }
    }
    //Only if the building has types, continue
    if ($contains_types) {
        //If it contains franchise or commercial, fetch commerce types
        if (str_contains($types, "commercial") || str_contains($types, "franchise")) {
            $stmt = $conn->prepare("SELECT commerce_types FROM buildings WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            
            $commerce_types = $result;
        }
        unset ($result);
        //If it contains frachise, fetch franchise info
        if (str_contains($types,"franchise")) {
            $stmt = $conn->prepare("SELECT franchise_owners FROM buildings WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $franchise_owners = $row["franchise_owners"];
            }
            $stmt->close();
            unset($result);
        }
        //check if house data is set
        if (str_contains($types,"house")) {
            $house_data_set = true;
            $stmt = $conn->prepare("SELECT contains_house FROM buildings WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if ($result && $result == "yes") {
                unset ($result);
                $stmt = $conn->prepare("SELECT other_bedrooms_house FROM buildings WHERE id=?");
                $stmt->bind_param("i",$building_id);
                $stmt->execute();
                $stmt->bind_result($result);
                $stmt->fetch();
                $stmt->close();
                if ($result) {
                    $other_bedrooms_house = $result;
                    unset ($result);
                }
            }
        }
        else {
            $house_data_set = false;
        }
        //Check if apartment data is set
        if (str_contains($types, "apartment")) {
            $stmt = $conn->prepare("SELECT contains_apartment FROM buildings WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if ($result && $result == "yes") {
                $apartment_data_set = true;
                $stmt = $conn->prepare("SELECT other_bedrooms_apartment,furniture_apartment FROM buildings WHERE id=?");
                $stmt->bind_param("i", $building_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $other_bedrooms_apartment = $row["other_bedrooms_apartment"];
                    $furniture_apartment = $row["furniture_apartment"];
                }
                unset($result);
            }
            else {
                $apartment_data_set = false;
            }
        }
    }

    //Convert results into arrays for processing
    $types_array = explode(",", $types);
    $types_ammount_array = explode(",", $types_ammount);
    $total_types = count($types_array);

    //Generate final sendback string
    $sendback_string = "";
    for ($i = 0; $i < $total_types; $i++) {
        $sendback_string = $sendback_string.$types_array[$i].";".$types_ammount_array[$i];
        if ($types_array[$i] == "commercial") {
            $sendback_string = $sendback_string.";".$commerce_types;
        }
        else if ($types_array[$i] == "franchise") {
            $sendback_string = $sendback_string.";".$franchise_owners.";".$commerce_types;
        }
        else if ($types_array[$i] == "house") {
            if ($house_data_set) {
                $sendback_string = $sendback_string.";".$other_bedrooms_house;
            }
            else {
                $sendback_string = $sendback_string.";"."none";
            }
        }
        else if ($types_array[$i] == "apartment") {
            if ($apartment_data_set) {
                $sendback_string = $sendback_string.";".$other_bedrooms_apartment.";".$furniture_apartment;
            }
            else {
                $sendback_string = $sendback_string.";"."none";
            }
        }
        $sendback_string = $sendback_string."-@-";
    }
    echo ($sendback_string);
?>