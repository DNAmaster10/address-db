<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";

    function error1($error) {
        $_SESSION["generic_error"] = $error;
        header ("Location: /pages/error/generic-error.php");
        die();
    }
    if (!isset($_SESSION["username"])) {
        error1("Your session has expired.");
    }
    else if (!isset($_SESSION["password"])) {
        error1("Your session has expired.");
    }
    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s",$_SESSION["username"]);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if ($result == $_SESSION["password"]) {
        $logged_in = true;
    }
    else {
        error1("There was an issue verifying your account.");
    }
    unset($result);
?>
