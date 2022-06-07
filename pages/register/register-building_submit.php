<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

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
        header ("Location: /pages/register/register-building.php";
        die();
    }
    if (!isset($_POST["street_name"])) {
        $_SESSION["building_error"] = "Please enter a valid street name for this building";
        header ("Location: /pages/register/register-building.php");
        die();
    }

    $district_name = $conn->real_escape_string($_POST["district"]);

    $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE district_name = s");
    $stmt->bind_param("s", $district_name);
    $stmt->execute();
    $result = $stmt->get_result();







?>
