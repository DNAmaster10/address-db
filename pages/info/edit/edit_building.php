<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";

    //check if id is set 
    if (!isset($_POST["id"])) {
        $_SESSION["generic_error"] = "No building ID was set";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    //check if id is a number
    if (!is_numeric($_POST["id"])) {
        $_SESSION["generic_error"] = "The building ID is invalid";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    //make sure id is int
    if (is_float($_POST["id"])) {
        $_SESSION["generic_error"] = "The building ID is invalid";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    //convert id to int
    $building_id = intval($_POST["id"]);

    //get building info from database
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic_error"] = "No building with that ID could be found";
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    $building_name = $result;
    unset($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/pages/edit_building.css">
        <title>Editing <?php echo ($building_name) ?></title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <div id="back_button_container">
                <form id="back_button_form" action="/pages/info/building_info.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo (strval($building_id)); ?>">
                    <input type="submit" value="Back">
                </form>
            </div>
            
        </div>
    </body>
</html>