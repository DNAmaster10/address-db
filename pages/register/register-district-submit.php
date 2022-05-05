<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (!isset($_POST["district_name"])) {
        $_SESSION["district_error"] = "Please enter a valid district name";
        header ("Location: /pages/register/register-district.php");
        die();
    }
    if (isset($_POST["code"])) {
        if (strlen($_POST["code"]) > 1) {
            $_SESSION["district_error"] = "Code too long. Maximim one character allowed.";
            header ("Location: /pages/register/register-district.php");
            die();
        }
        $code = $conn->real_escape_string($_POST["code"]);
        $code = strtoupper($code);
        $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE postcodeChar = ?";
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($result);
    }
?>
