<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (isset($_SESSION["district_error"])) {
        $error_message = $_SESSION["district_error"];
        unset($_SESSION["district_error"];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register district</title>
    </head>
    <body>
        <p>District name</p>
        <form action="register-district_submit.php" method="POST">
            <input type="text" placeholder="District name" name="district_name">
            <p>Postcode character (leave empty to autogenerate this)</p>
            <input type="text" name="code" maxlength="1" placeholder="e.g: A, B, 1, 2">
            <input type="submit" value="Create district">
        </form>
        <p id="error_message"><?php if (isset($error_message)) {echo $error_message;} ?></p>
    </body>
</html>
