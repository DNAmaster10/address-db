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
