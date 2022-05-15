<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    if (!isset($_POST["unit_name"])) {
        $_SESSION["street_unit_error"] = "Please enter a name for the street unit";
        header ("Location: /pages/register/register-street_unit");
        die();
    }
    if (!isset($_POST["district"])) {
        $_SESSION["street_unit_error"] = "Please select a parent district";
        header ("Location: /pages/register/register-street_unit");
        die();
    }
    $unit_name = $conn->real_escape_string ($_POST["street_unit"]);

?>
