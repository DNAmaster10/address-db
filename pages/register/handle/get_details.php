<?php
    #Initialization
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";

    #Make sure input is valid
    if (!isset($_GET["coords"])) {
        echo ("error");
        die();
    }
    $coords_string = $conn->real_escape_string($_GET["coords"]);
    if (!str_contains($coords_string,",")) {
        echo ("error");
        die();
    }
    $coords_array = explode(",",$coords_string);
    if (count($coords_array) > 2) {
        echo ("error");
        die();
    }
    if (!is_numeric($coords_array[0]) or !is_numeric($coords_array[1])) {
        echo ("error");
        die();
    }

    #Find out the district
    $coord = $coords_string;
    function get_district($coords) {
       include $_SERVER["DOCUMENT_ROOT"]."/includes/find_district.php";
       return ($district);
    }
    if ($district == "error1") {
        echo ("No district was found for that co-ordinate");
        die();
    }
    else {
        include $_SERVER["DOCUMENT_ROOT"]."/includes/find_street_unit.php";
        if ($street_unit == "error1") {
            echo ("No street unit was found for that co-ordinate");
        }
        else {
            echo ($district."#-#".$street_unit);
        }
    }
?>
