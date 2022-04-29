<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"].'/includes/dbh.php';
    if (!isset($_POST["username"])) {
        $_SESSION["login_error"] = "Enter a valid username";
        header ("Location: /pages/login/login.php");
        die();
    }
    else if (!isset($_POST["password"])) {
        $_SESSION["login_error"] = "Enter a valid password";
        header ("Location: /pages/login/login.php");
        die();
    }
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute()
    $stmt->bind_result($result);

?>
