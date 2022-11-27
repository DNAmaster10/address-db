<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Districts</title>
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
            <form action="/pages/register/register-district.php">
                <input type="submit" value="Create district">
            </form>
            <form action="/pages/user/user-home.php">
                <input type="submit" value="Back">
            </form>
        </div>
    </body>
</html>
