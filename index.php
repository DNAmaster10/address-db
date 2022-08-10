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
                    echo "<li><a href='/pages/login/logout.php'>Logout</a></li>";
                }
                else {
                    echo "<li><a href='/pages/login/login.php'>Login</a></li>";
                }
            ?>
        </ul>
        <h1>Welcome to Kaloro-db</h1>
    </body>
</html>
