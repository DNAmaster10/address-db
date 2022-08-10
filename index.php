<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro db</title>
    </head>
    <body>
        <ul>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='login_button'><a href='/pages/login/logout.php'>Logout</a></li>";
                }
                else {
                    echo "<li id='login_button'><a href='/pages/login/login.php'>Login</a></li>";
                }
            ?>
        </ul>
        <div id="index_bg_img">
            <img src="/media/images/src/index-bg-1.jpg" style="width: 100%;">
        </div>
        <h1>Welcome to Kaloro-db</h1>
    </body>
</html>
