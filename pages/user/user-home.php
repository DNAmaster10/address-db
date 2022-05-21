<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
    </head>
    <body>
        <h1>Welcome back, <?php echo $_SESSION["username"]; ?>.</h1>
        <div id="building_container">
            <form action="/pages/register/register-building.php">
                <input type="submit" value="Register building">
            </form>
        </div>
        <div id="street_container">
            <form action="/pages/register/street-unit-home.php">
                <input type="submit" value="Street units">
            </form>
        </div>
        <div id="district_container">
            <form action="/pages/register/district-home.php">
                <input type="submit" value="Districts">
            </form>
        </div>
    </body>
</html>
