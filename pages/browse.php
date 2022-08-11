<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>refweM</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
    <ul class="navbar_container_ul" id="navbar_container">
            <li id="home_button_li" class="left"><a href="/index.php" class="navbar_button">Home</a></li>
            <li id="browse_button_li" class="left"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='' class='left'><a href='/pages/register/register'";
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
                }
                else {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
                }
            ?>
        </ul>
        <form action="/pages/district_submit.php" method="POST">
            <input type="text" placeholder="x,y" name="coords">
            <input type="submit" value="submit">
        </form>
    </body>
</html>
