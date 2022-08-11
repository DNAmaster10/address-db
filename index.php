<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro db</title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/index.css">
    </head>
    <body>
        <ul class="navbar_container_ul">
            <li id="browse_button_li" class="navbar_button_li"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='login_button_li' class='navbar_button_li'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
                }
                else {
                    echo "<li id='login_button_li' class='navbar_button_li'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
                }
            ?>
        </ul>
        <div id="index_bg_img">
            <img src="/media/images/src/index-bg-1.jpg" style="width: 100%;" id="index_bg_img_img">
        </div>
        <h1>Welcome to Kaloro-db</h1>
        <p>Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text Long text </p>
    </body>
</html>
