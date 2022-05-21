<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Street units</title>
    </head>
    <body>
        <p>Street Units</p>
        <form action="/pages/user/user-home.php">
            <input type="submit" value="Back">
        </form>
        <form action="/pages/register/register-street_unit.php">
            <input type="submit" value="Register street unit">
        </form>
    </body>
</html>
