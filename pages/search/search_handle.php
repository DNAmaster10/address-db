<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    if (!isset($_POST["search_term"])) {
        echo "error";
        die();
    }
    if (!isset($_POST["search_categories"])) {
        echo "error";
        die();
    }
    $search_term = $conn->real_escape_string($_POST["search_term"]);
    $search_term = strtolower($search_term);
    $search_term_first = substr($search_term, 0);
    $search_categories = $conn->real_escape_string($_POST["search_categories"]);
    $search_categories_array = explode(",", $search_categories);
    $category_length = count($search_categories_array);
    $sendback_string = "&_&";
    $like_operator = '%'.$search_term.'%';
    for ($i = 0; $i < $category_length; $i++) {
        if ($search_categories_array[$i] == "districts") {
            $sendback_string = $sendback_string."district:!:";
            //search function for districts
            $district_string = "~-~";
            $stmt = $conn->prepare("SELECT district_id,district_name FROM districts WHERE lower(district_name)=? OR lower(district_name) LIKE ? LIMIT 10;");
            $stmt->bind_param("ss",$search_term,$like_operator);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $district_string = $district_string.$row["district_name"]."#-#".$row["district_id"]."~-~";
            }
            $stmt->close();
            unset($result);

            $stmt = $conn->prepare("SELECT district_id,district_name FROM districts WHERE lower(postcodeChar)=? OR lower(postcodeChar)=? LIMIT 10;");
            $stmt->bind_param("ss",$search_term,$search_term_first);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $district_string = $district_string.$row["district_name"]."#-#".$row["district_id"]."~-~";
            }
            $stmt->close();
            unset($result);

            //Send result to main return string
            $sendback_string = $sendback_string.$district_string."&_&";
            unset($district_string);
        }
        if ($search_categories_array[$i] == "street_units") {
            $sendback_string = $sendback_string."street_units:!:";
            //search function for street units
            $street_unit_string = "~-~";
            $stmt = $conn->prepare("SELECT id,name FROM street_units WHERE lower(name)=? OR lower(name) LIKE ? LIMIT 10;");
            $stmt->bind_param("ss", $search_term,$like_operator);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $street_unit_string = $street_unit_string.$row["name"]."#-#".$row["id"]."~-~";
            }
            $stmt->close();
            unset($result);

            $stmt = $conn->prepare("SELECT id,name FROM street_units WHERE lower(postcodeChar)=? OR lower(full_postcode)=? LIMIT 10;");
            $stmt->bind_param("ss", $search_term,$search_term);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $street_unit_string = $street_unit_string.$row["name"]."#-#".$row["id"]."~-~";
            }
            $stmt->close();
            unset ($result);

            //Send result to main return string
            $sendback_string = $sendback_string.$street_unit_string."&_&";
        }
        if ($search_categories_array[$i] == "streets") {
            $sendback_string = $sendback_string."streets:!:";
            //search function for streets
            $streets_string = "~-~";
            $stmt = $conn->prepare("SELECT id,street FROM streets WHERE street LIKE ? LIMIT 10;");
            $stmt->bind_param("s", $like_operator);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $streets_string = $streets_string.$row["street"]."#-#".$row["id"]."~-~";
            }
            $stmt->close();
            unset ($result);

            //Send result to main return string
            $sendback_string = $sendback_string.$streets_string."&_&";
        }
        if ($search_categories_array[$i] == "buildings") {
            $sendback_string = $sendback_string."buildings:!:";
            //search function for buildings
            $buildings_string = "~-~";
            $stmt = $conn->prepare("SELECT id,building_name FROM buildings WHERE building_name LIKE ? OR postcode LIKE ? LIMIT 10;");
            $stmt->bind_param("ss", $like_operator,$like_operator);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $buildings_string = $buildings_string.$row["building_name"]."#-#".$row["id"]."~-~";
            }
            $stmt->close();
            unset ($result);

            //Send result to main return string
            $sendback_string = $sendback_string.$buildings_string."&-&";
        }
    }

    echo ($sendback_string);

    //sendback 
    //"district:!:name#-#link~-~name#-#link&_&district:!:name#-#link~-~name#-#link&_&"
?>