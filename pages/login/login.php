<?php
    session_start();
    if (isset($_SESSION["login_error"])) {
        $error_message = $_SESSION["login_error"];
        unset ($_SESSION["login_error"];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro-db</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="/pages/login/login_submit.php" method="POST">
            <input type="text" name="username" placeholder="username">
            <input type="text" name="password" placeholder="password">
            <input type="submit" value="login">
        </form>
        <?php
            if (isset($error_message)) {
                echo '<p>'.$error_message.'</p>';
            }
        ?>
    </body>
</html>
