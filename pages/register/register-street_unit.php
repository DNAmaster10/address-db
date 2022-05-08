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
            <input type="text" placeholder="Unit name" name="street_unit"
