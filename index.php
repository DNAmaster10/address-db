<?php
    session_start();
    if (isset($_SESSION["search_error"])) {
        $error_message = $_SESSION["search_error"];
        unset($_SESSION["search_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro db</title>
    </head>
    <body>
        <form action="/pages/login/login.php">
            <input type="submit" value="login">
        </form>
        <form action="/pages/search/search_submit.php" method="GET">
            <input type="text" placeholder ="XYZ XYZ" name="search_term" required>
            <input type="submit" value="Search">
        </form>
        <?php
            if (isset($error_message)) {
                echo "<p>".$error_message."</p>";
            }
        ?>
    </body>
</html>
