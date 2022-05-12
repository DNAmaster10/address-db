<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>refweM</title>
    </head>
    <body>
        <form action="/pages/district_submit.php" method="POST">
            <input type="text" placeholder="x,y" name="coords">
            <input type="submit" value="submit">
        </form>
    </body>
</html>
