<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Street units</title>
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
            <p>Street Units</p>
            <form action="/pages/user/user-home.php">
                <input type="submit" value="Back">
            </form>
            <form action="/pages/register/register-street_unit.php">
                <input type="submit" value="Register street unit">
            </form>
        </div>
    </body>
</html>
