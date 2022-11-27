<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (isset($_SESSION["street_unit_error"])) {
        $error = $_SESSION["street_unit_error"];
        unset($_SESSION["street_unit_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Street Unit</title>
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
            <form action="/pages/register/street-unit-home.php">
                <input type="submit" value="Back">
            </form>
            <form action="/pages/register/register-street_unit_submit.php" method="POST">
                <p>Street unit name: </p>
                <input type="text" placeholder="Unit name" name="street_unit" required>
                <p>Postcode char (leave blank to autogenerate)</p> 
                <input type="text" placeholder="Unit code" name="unit_code" maxlength="1">
                <select name="district">
                    <?php
                        $stmt = $conn->prepare("SELECT district_name FROM districts");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='".$row["district_name"]."'>".$row["district_name"]."</option>";
                        }
                    ?>
                </select>
                <p>Make sure co-ords entered are in exact format, and are correct before submiting.</p>
                <input type="text" name="coords" placeholder="(x,y).(x,y).(x,y)">
                <input type="submit" value="Register">
            </form>
            <p>
                <?php if (isset($error)) {
                    echo ($error);
                }
                ?>
            </p>
        </div>
    </body>
</html>
