<?php
    session_start();
    if (isset($_SESSION["login_error"])) {
        $error_message = $_SESSION["login_error"];
        unset ($_SESSION["login_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro-db</title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/login.css">
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="login_container" class="login_container">
            <h1>Login</h1>
            <form action="/pages/login/login_submit.php" method="POST">
                <input type="text" name="username" placeholder="username">
                <br>
                <input type="text" name="password" placeholder="password">
                <br>
                <input type="submit" value="login">
            </form>
            <?php
                if (isset($error_message)) {
                    echo '<p>'.$error_message.'</p>';
                }
            ?>
        </div>
    </body>
</html>
