<?php
    $contains_types = false;
    //uses $building_id. Requires database connection.
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
        //If it contains frachise, fetch franchise info
        if (str_contains($types,"franchise")) {
            $stmt = $conn->prepare("SELECT franchise_owners,commerce_types FROM buildings WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $franchise_owners = $row["framchise_owners"];
                $commerce_types = $row["commerce_types"];
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
                $result = $stmt->get_result($result);
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
    $types_ammount_array = explode(",", $types_ammount_array);
    $total_types = count($types_array);
    for ($i = 0; $i < $total_types; $i++) {
        echo "test";
    }
?>