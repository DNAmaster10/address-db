<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (!isset($_POST["district_name"])) {
        $_SESSION["district_error"] = "Please enter a valid district name";
        header ("Location: /pages/register/register-district.php");
        die();
    }
    if (!isset($_POST["colour_code"])) {
        $_SESSION["district_error"] = "Please select a colour";
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
        $colour_code = $conn->real_escape_string($_POST["colour_code"]);
        $district_name = $conn->real_escape_string($_POST["district_name"]);
        $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE postcodeChar = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (strlen($result) > 0) {
            $_SESSION["district_error"] = "A district with that code already exists.";
            header ("Location: /pages/register/register-district.php");
            die();
        }
        else {
            unset($result);
            $stmt = $conn->prepare("INSERT INTO districts (district_name,district_colour,postcodeChar) VALUES (?,?,?)");
            $stmt->bind_param("sss",$district_name,$colour_code,$code);
            $stmt->execute();
            $stmt->close();
            header("Location: /pages/register/district-home.php");
            die();
        }
    }
?>
