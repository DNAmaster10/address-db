<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    if (!isset($_POST["unit_name"])) {
        $_SESSION["street_unit_error"] = "Please enter a name for the street unit";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    $unit_name = $conn->real_escape_string ($_POST["street_unit"]);
    $stmt = $conn->prepare("SELECT name FROM street_units WHERE name=?");
    $stmt->bind_param("s",$unit_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if ($result) {
        $_SESSION["street_unit_error"] = "A street unit with that name already exists";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    if (!isset($_POST["district"])) {
        $_SESSION["street_unit_error"] = "Please select a parent district";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    $district = $conn->real_escape_string($_POST["district"]);
    if (isset($_POST["unit_code"])) {
        $code = $conn->real_escape_string($_POST["unit_code"]);
        if (strlen($code) > 1) {
            $_SESSION["street_unit_error"] = "Enter a code 1 character long";
            header ("Location: /pages/register/register-street_unit.php");
            die();
        }
        $stmt = $conn->prepare ("SELECT name FROM street_units WHERE postcodeChar = ?");
        $stmt->bind_param("s",$code);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if ($result) {
            $_SESSION["street_unit_error"] = "A unit with that code already exists";
            header ("Location: /pages/register/register-street_unit.php");
            die();
        }
    }
    else {
        $current_letter = "A";
        $loop_count = 1;
        $empty_found = false;
        while ($loop_count != 26 && $empty_found == false) {
            $stmt->bind_param("s",$current_letter);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if (!$result) {
                $code = $current_letter;
                unset ($result);
                $empty_found = true;
            }

        }
    }

?>
