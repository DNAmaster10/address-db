<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Buildings</title>
        <link rel="stylesheet" href="/css/main.css">
        <style>
            body {
                padding: 0px;
                margin: 0px;
            }
            #main_container {
                padding: 5px;
            }

        </style>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="main_container">
            <p>Buildings</p>
            <form action="/pages/user/user-home.php">
                <input type="submit" value="Back">
            </form>
            <form action="/pages/register/register-building.php">
                <input type="submit" value="Register building">
            </form>
        </div>
</html>
