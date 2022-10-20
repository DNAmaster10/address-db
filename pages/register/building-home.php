<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Buildings</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <p>Buildings</p>
        <form action="/pages/user/user-home.php">
            <input type="submit" value="Back">
        </form>
        <form action="/pages/register/register-building.php">
            <input type="submit" value="Register building">
        </form>
</html>
