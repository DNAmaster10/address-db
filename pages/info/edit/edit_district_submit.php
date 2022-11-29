<?php
    //database stuff and check login
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    //Error function
    function error($error) {
        $_SESSION["generic_error"] = $error;
        header("Location: /pages/error/generic-error.php");
        die();
    }

    //Make sure POST vars are set and valid
    if (!isset($_POST["id"])) {
        error("No district ID was set");
    }
    if (!isset($_POST["district_name"])) {
        error("No district name was set");
    }
    if (!isset($_POST["district_colour"])) {
        error("No district colour was set");
    }
    //check ID
    if (!is_numeric($_POST["id"])) {
        error("The district ID was invalid");
    }

    //Check if district exists in database with ID
    $id = intval($_POST["id"]);
    $stmt = $conn->prepare("SELECT district_name FROM districts WHERE district_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        error("District could not be found in database");
    }
    else {
        $current_district_name = $result;
    }
    unset($result);

    error_log("Test ".$_POST["district_name"]);
    //Check if the district name needs changing, if so, change it
    if (!$current_district_name == $_POST["district_name"]) {
        //Check if another district with that name already exists
        $new_district_name = $_POST["district_name"];

        $stmt = $conn->prepare("SELECT district_id FROM districts WHERE district_name=? AND district_id!=?");
        $stmt->bind_param("si", $new_district_name, $id);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if ($result) {
            error("Another district with that name already exists");
        }

        //Update district data
        $stmt = $conn->prepare("UPDATE districts SET district_name=? WHERE district_id=?");
        $stmt->bind_param("si", $new_district_name, $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE street_units SET parent_district=? WHERE parent_district=?");
        $stmt->bind_param("ss", $current_district_name, $new_district_name);
        $stmt->execute();
        $stmt->close();
    }

    //Update district colour
    $stmt = $conn->prepare("UPDATE districts SET district_colour=? WHERE district_id=?");
    $stmt->bind_param("si", $_POST["district_colour"], $id);
    $stmt->execute();
    $stmt->close();

    header ("Location: /pages/info/district_info.php?id=$id");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <p>If you are seeing this page, an error has occured</p> 
    </body>
</html>