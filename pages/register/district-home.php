<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Districts</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <form action="/pages/register/register-district.php">
            <input type="submit" value="Create district">
        </form>
        <form action="/pages/user/user-home.php">
            <input type="submit" value="Back">
        </form>
    </body>
</html>
