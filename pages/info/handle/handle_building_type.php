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
            $stmt = $conn->prepare("SELECT contains_house FROM buildgins WHERE id=?");
            $stmt->bind_param("i", $building_id);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if ($result && $result == "yes") {
                $stmt = $conn->prepare("SELECT other_bedrooms_house FROM buildgins WHERE id=?");
                $stmt->bind_param("i",$building_id);
                $stmt->execute();
                $stmt->bind_result($result);
                $stmt->fetch();
                $stmt->close();
                if ($result) {
                    $other_bedrooms_house = $result;
                }
            }
        }
    }

?>