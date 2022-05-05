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
        <form action="/pages/register/register-district">
            <input type="submit" value="Create district">
        </form>

    </body>
</html>
