<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include ($_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php");

    function error($error) {
        $_SESSION["generic_error"] = $error;
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    //Make sure ID is set and that it's valid
    if (!isset($_GET["id"])) {
        error("No district ID was set");
    }
    if (!is_numeric($_GET["id"])) {
        error("Invalid ID");
    }
    $id = intval($_GET["id"]);
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
        $district_name = $result;
    } 
    unset ($result);

    //Get other district details
    $stmt = $conn->prepare("SELECT district_colour,postcodeChar FROM districts WHERE district_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $district_colour = $row["district_colour"];
        $postcodeChar = $row["postcodeChar"];
    }
    $stmt->close();
    unset ($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo("Editing ".$district_name); ?></title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/pages/edit_district.css">
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <form id="back_button_form" action="/pages/info/district_info.php?id=<?php echo ($id); ?>">
                <input type="submit" value="Back">
            </form>
            <form id="edit_district_form" action="/pages/info/edit/edit_district_submit.php" method="POST">
                <input type="hidden" name="id" value="<?php echo(strval($id)); ?>">
                <p id="info_text">District Name: </p><input type="text" name="district_name" placeholder="District Name" value="<?php echo ($district_name); ?>">
                <br>
                <p id="info_text">District Colour: </p><input type="color" name="district_colour" value="<?php echo ($district_colour) ?>">
                <br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </body>
</html>