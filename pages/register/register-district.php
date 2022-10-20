<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (isset($_SESSION["district_error"])) {
        $error_message = $_SESSION["district_error"];
        unset($_SESSION["district_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register district</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <form action="/pages/register/district-home.php">
            <input type="submit" value="Back">
        </form>
        <p>District name</p>
        <form action="/pages/register/register-district_submit.php" method="POST">
            <input type="text" placeholder="District name" name="district_name" required>
            <p>Postcode character (leave empty to autogenerate this)</p>
            <input type="text" name="code" maxlength="1" placeholder="e.g: A, B, 1, 2">
            <input type="color" name="colour_code"  value="#0000ff">
            <p>Enter the co-ordinates below, starting from the first corner of the district, in the order the borders connect. y, in this case, corresponds to the in-game z axis. A minimum of 3 points must be entered. Make sure syntax is EXACT, or many errors will be present down the line.</p>
            <input type="text" name="points" placeholder="(x,y).(x,y).(x,y)" required>
            <input type="submit" value="Create district">
        </form>
        <p id="error_message"><?php if (isset($error_message)) {echo $error_message;} ?></p>
    </body>
</html>
