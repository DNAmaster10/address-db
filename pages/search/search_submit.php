<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    if (strlen($_GET["seach_term"]) < 1) {
        $_SESSION["search_error"] = "Please enter a valid search term";
        header ("Location: /index.php");
        die();
    }
    $search_term = $conn->real_escape_string($_GET["search_term"]);
    $stmt = $conn->prepare("SELECT district_name FROM districts WHERE postcodeChar=?");
    $stmt->bind_param("s",$search_term);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (strlen($result) > 0) {
        $districts = $result;
        $result_ammount = $result_ammount + 1;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $search_term; ?></title>
    </head>
    <body>
        <h1><?php echo $result_ammount; ?> results found</h1>
        
    </body>
</html>
