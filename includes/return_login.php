<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    if (!isset($_SESSION["username"])) {
        $logged_in = false;
    }
    else if (!isset($_SESSION["password"])) {
        $logged_in = false;
    }
    else {
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
            $logged_in = false;
        }
        unset($result);
    }
?>
