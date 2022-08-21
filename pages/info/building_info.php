<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";
    //Make sure variables for searching are set
    if (!isset($_POST["id"])) {
        $_SESSION["generic_error"] = "POST variable 'id' is not set";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    //Check to make sure building exists in database
    $building_id = $conn->real_escape_string($_POST["id"]);
    $building_id = intval($building_id);
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic_error"] = "Building not found in database";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    //Grab data from database
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Building info</title>
    </head>
</html>