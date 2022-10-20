<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/user-home.css">
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="user-home_container" class="user-home_container">
            <h1>Welcome back, <?php echo $_SESSION["username"]; ?>.</h1>
            <p id="description">This page is only visible to users with an account. Use this page to register new buildings, districts and street units.</p>
            <div id="building_container">
                <form action="/pages/register/building-home.php">
                    <input type="submit" value="Buildings">
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
        </div>
    </body>
</html>
