<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";

    function error($error) {
        $_SESSION["generic_error"] = $error;
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    if (!isset($_GET["id"])) {
        error("No district ID was set");
    }
    if (!is_numeric($_GET["id"])) {
        error("Invalid ID");
    }
    $id = intval($_GET["id"]);
    //Check if ID exists in database and gets district name
    $stmt = $conn->prepare("SELECT district_name FROM districts WHERE district_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        error("District with the id ".strval($id)." not found in database");
    }
    else {
        $district_name = $result;
    }
    unset ($result);

    //Get other district details
    $stmt = $conn->prepare("SELECT district_colour,postcodeChar,points FROM districts WHERE district_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    while ($row = $result->fetch_assoc()) {
        $district_colour = $row["district_colour"];
        $postcode_char = $row["postcodeChar"];
        $points = $row["points"];
    }
    unset ($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $district_name; ?></title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/pages/district_info.css">
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <h1><?php echo ($district_name); ?></h1>
            <div id="side_box">
                <p class="info_text">Postcode Char: <?php echo ($postcode_char); ?></p>
                <p class="info_text">District Colour: <?php echo ($district_colour); ?></p>
                <div id="colour_box">
                </div>
            </div>
        </div>
    </body>
</html>