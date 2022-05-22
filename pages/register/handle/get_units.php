<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    #include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    echo ("Got to 1");
    if (!isset($_GET["district"])) {
        echo ("error");
    }
    else {
        echo ("Got to 2");
        $district = $conn->real_escape_string($_GET["district"]);
        $stmt = $conn->prepare("SELECT name FROM street_units WHERE parent_district=?");
        $stmt->bind_param("s",$district);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo ($row["parent_district"]."#-#");
            echo ("street units");
        }
    }
?>
