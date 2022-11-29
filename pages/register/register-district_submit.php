<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    
    function error($error) {
        $_SESSION["district_error"] = $error;
        header ("Location: /pages/register/register-district.php");
        die();
    }
    if (!isset($_POST["district_name"])) {
        error("Please enter a valid district name");
    }
    if (!isset($_POST["colour_code"])) {
        error("Please select a colour");
    }
    if (!isset($_POST["points"]) or strlen($_POST["points"]) < 16) {
        error("Please enter at least three points for the district border");
    }
    $points = $_POST["points"];
    if ((substr_count($points, ".")) < 2) {
        error("Please enter at least three points for the district border");
    }
    $points = str_replace("(", "", $points);
    $points = str_replace(")", "", $points);

    $district_name = $_POST["district_name"];
    $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE district_name=?");
    $stmt->bind_param("s", $district_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (strlen($result) > 0) {
        error("A district with that name already exists");
    }
    if (strlen($_POST["code"]) > 0) {
        if (strlen($_POST["code"]) > 1) {
            error("Code too long. Maximim one character allowed.");
        }
        $code = $_POST["code"];
        $code = strtoupper($code);
        $colour_code = $_POST["colour_code"];
        $stmt = $conn->prepare("SELECT postcodeChar FROM districts WHERE postcodeChar = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        if (strlen($result) > 0) {
            error("A district with that code already exists.");
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
    else {
        $colour_code = $_POST["colour_code"];
        $current_letter = "A";
        $loop_count = 1;
        $empty_found = false;
        while ($loop_count != 27 && $empty_found == false) {
            $stmt = $conn->prepare("SELECT district_name FROM districts WHERE postcodeChar=?");
            $stmt->bind_param("s",$current_letter);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if (!$result) {
                unset ($result);
                $empty_found = true;
                $stmt = $conn->prepare("INSERT INTO districts (district_name,district_colour,postcodeChar,points) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss",$district_name,$colour_code,$current_letter,$points);
                $stmt->execute();
                header ("Location: /pages/register/district-home.php");
                die();
            }
            else {
                $loop_count++;
                $current_letter = ++$current_letter;
                unset($result);
            }
        }
        $loop_count = 0;
        $current_letter = "0";
        while ($loop_count != 10 && $empty_found == false) {
            $stmt = $conn->prepare("SELECT district_name FROM districts WHERE postcodeChar=?");
            $stmt->bind_param("s",$current_letter);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if (!$result) {
                unset ($result);
                $empty_found = true;
                $stmt = $conn->prepare("INSERT INTO districts (district_name,district_colour,postcodeChar,points) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss",$district_name,$colour_code,$current_letter,$points);
                $stmt->execute();
                header ("Location: /pages/register/district-home.php");
                die();
            }
            else {
                $current_letter = ++$current_letter;
                $loop_count++;
                unset($result);
            }
        }
        error("The maximim ammount of districts for this city has been reached");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>error</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <p>If you are seeing this page, a serious error has occured</p>
    </body>
</html>
