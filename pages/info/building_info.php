<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";
    //Make sure variables for searching are set
    if (!isset($_POST["id"])) {
        $_SESSION["generic_error"] = "POST variable 'id' is not set";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    //Check to make sure building exists in database
    $building_id = $conn->real_escape_string($_POST["id"]);
    $building_id = intval($building_id);
    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE id=?");
    $stmt->bind_param("i",$building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $_SESSION["generic_error"] = "Building not found in database";
        header ("Location: /pages/error/generic_error.php");
        die();
    }
    else {
        $building_name = $result;
    }
    unset($result);
    //Grab data from database
    //Grab postcode
    $stmt = $conn->prepare("SELECT postcode FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $postcode = "none";
    }
    else {
        $postcode = $result;
    }
    unset($result);
    //Grab district
    $stmt = $conn->prepare("SELECT parent_district FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $district = "none";
    }
    else {
        $district = $result;
    }
    unset($result);
    //Grab street unit
    $stmt = $conn->prepare("SELECT parent_street_unit FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street_unit = "none";
    }
    else {
        $street_unit = $result;
    }
    unset($result);
    //Grab street
    $stmt = $conn->prepare("SELECT parent_street FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $street = "none";
    }
    else {
        $street = $result;
    }
    unset($result);
    //Grab description
    $stmt = $conn->prepare("SELECT description FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $description = "none";
    }
    else {
        $description = $result;
    }
    unset($result);
    //Grab population
    $stmt = $conn->prepare("SELECT population FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $population = "0";
    }
    else {
        $population = $result;
    }
    unset($result);
    //Grab construction date
    $stmt = $conn->prepare("SELECT construction_date FROM buildings WHERE id=?");
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        $construction_date = "Unknown";
    }
    else {
        $construction_date = $result;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo($building_name); ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="/css/main.css">
    </head> 
    <body>
        <ul class="navbar_container_ul" id="navbar_container">
            <li id="home_button_li" class="left"><a href="/index.php" class="navbar_button">Home</a></li>
            <li id="browse_button_li" class="left"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
                    echo "<li id='tools_button_li' class='right'><a href='/pages/user/user-home.php' class='navbar_button'>Tools</a></li>";
                }
                else {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
                }
            ?>
        </ul>
        <div id="main_container">
            <h2><?php echo $building_name; ?></h2>
            <div id="side_box">
                <p class="info_text">Postcode: <?php echo($postcode); ?></p>
                <p class="info_text">District: <?php echo($district); ?></p>
                <p class="info_text">Street Unit: <?php echo($street_unit); ?></p>
                <p class="info_text">Street: <?php echo($street); ?></p>
                <p class="info_text">Population: <?php echo($population); ?></p>
                <p class="info_text">Construction Date: <?php echo($construction_date); ?></p>
                <p class="info_text">Description: <?php echo($description); ?></p>
            </div>
            <div id="inner_building_container">

            </div>
            <div id="logged_in_container">
                <?php if($logged_in) {
                    echo ("<form id='edit_button' action='/pages/info/edit/edit_building.php'><input type='hidden' value='" + strval($building_id) +  "' name='id'><input type='submit' value='Edit' method='POST'></form>");
                } ?>
            </div>
        </div>
    </body>
</html>