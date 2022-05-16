<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    #Get the street unit name
    if (!isset($_POST["unit_name"])) {
        $_SESSION["street_unit_error"] = "Please enter a name for the street unit";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    #Make sure a street unit with that name does not already exist
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
    #Get district
    if (!isset($_POST["district"])) {
        $_SESSION["street_unit_error"] = "Please select a parent district";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    $district = $conn->real_escape_string($_POST["district"]);
    #Make sure district exists
    $stmt = $conn->prepare("SELECT postcodeChar FROM district WHERE district_name=?");
    $stmt->bind_param("s",$district);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["street_unit_error"] = "A district with that name does not exist.";
        header ("Location: /pages/register/register-street_unit.php");
        die();
    }
    $district_code = $result;
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
            $stmt->prepare("SELECT name FROM street_unit WHERE postcodeChar=?");
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
            $loop_count++;
        }
        if (!$empty_found) {
            $loop_count = 0;
            $current_letter = "0";
            while ($loop_count < 10 && $empty_found == false) {
                $stmt->prepare("SELECT name FROM street_unit WHERE postcodeChar=?");
                $stmt->bind_param("s",$current_letter);
                $stmt->execute();
                $stmt->bind_result($result);
                $stmt->fetch();
                $stmt->close();
                if (!$result) {
                    $empty_found = true;
                    $code = $current_letter;
                    unset($result);
                }
                $loop_count++;
            }
        }
        if (!$empty_found) {
            $_SESSION["street_unit_error"] = "The maximum number of street units for this district has been reached.";
            header ("Location: /pages/register/register-street_unit.php");
            die();
        }
        else {
            $full_code = $disrict_code + $code;
            $coords = $conn->real_escape_string($_POST["coords"]);
            $stmt->prepare("INSERT INTO street_units (name,postcodeChar,full_postcode,parent_distict,points) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss",$unit_name,$code,$full_code,$district,$coords);
            $stmt->execute();
            header ("Location: /pages/register/register-street_unit.php");
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <p>If you are seeing this page a serious error has occured</p>
    </body>
</html>
