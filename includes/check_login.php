<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    if (!isset($_SESSION["username"])) {
        $_SESSION["generic_error"] = "Your session has expired.";
        header ("location: /pages/error/generic-error.php");
        die();
    }
    else if (!isset($_SESSION["password"])) {
        $_SESSION["generic_error"] = "Your session has expired.";
        header ("location: /pages/error/generic_error.php");
        die();
    }
    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s",$_SESSION["username"]);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if ($result == $password) {
        $logged_in = true;
    }
    else {
        $_SESSION["generic_error"] = "There was an issue verifying your account.";
        $logged_in = false;
        header ("Location: /pages/error/generic-error.php");
        die();
    }
?>
