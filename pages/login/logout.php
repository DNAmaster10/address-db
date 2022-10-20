<?php
    session_start();
    $username = $_SESSION["username"];
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Logged out</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <p>Later, <?php echo $username; ?></p>
        <form action="/index.php">
            <input type="submit" value="Home">
        </form>
    </body>
</html>
