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
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if ($result == $password) {
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        header ("Location: /pages/user/user-home.php");
        die();
    }
    else {
        $_SESSION["login_error"] = "Username or password invalid";
        header ("Location: /pages/login/login.php");
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <p>If you are seeing this, an unknown error has occured>
        <form action="index.php">
            <input type="submit" value="Home">
        </form>
    </body>
</html>
