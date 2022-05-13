<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    $coord = $conn->real_escape_string($_POST["coords"]);
    include $_SERVER["DOCUMENT_ROOT"]."/includes/find_district.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>dwe</title>
    </head>
    <body>
        <p>
        <?php 
        for ($i=0; $i<count($probability_district_array); $i++){
            echo (strval($probability_district_array[$i])."  "); 
        }
        ?>
        </p>
    </body>
</html>
