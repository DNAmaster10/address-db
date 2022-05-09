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
    </head>
    <body>
        <form action="/pages/register/register-street_unit_submit.php">
            <p>Street unit name: </p>
            <input type="text" placeholder="Unit name" name="street_unit" required>
            <p>Postcode char (leave blank to autogenerate)</p> 
            <input type="text" placeholder="Unit code" name="unit_code">
            <select name="district">
                <?php
                    $stmt = $conn->prepare("SELECT district_name FROM districts");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        
                    }
                ?>
            </select>
