<?php
    session_start();
    if (isset($_SESSION["generic_error"])) {
        $error_message = $_SESSION["generic_error"];
    }
    else {
        $error_message = "An error occured";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                margin: 0px;
                padding: 0px;
            }
            #error_message_container {
                padding: 5px;
            }
        </style>
        <link rel="stylesheet" href="/css/main.css">
        <title>Error</title>
    </head>
    <body>
        <?php include $_SERVER["DOCUMENT_ROOT"]."/includes/html/header.php"; ?>
        <div id="error_message_container">
        <p><?php echo $error_message; ?></p>
        <form action="/index.php">
            <input type="submit" value="Home">
        </form>
        </div>
    </body>
</html>
