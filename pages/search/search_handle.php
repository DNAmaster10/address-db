<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    if (!isset($_POST["search_term"])) {
        echo "error";
        die();
    }
    if (!isset($_POST["search_categories"])) {
        echo "error";
        die();
    }
    $search_term = $conn->real_escape_string($_POST["search_term"]);
    $search_term = strtolower($search_term);
    $search_categories = $conn->real_escape_string($_POST["search_categories"]);
    $search_categories_array = explode(",", $search_categories);
    $category_length = count($search_categories_array);
    $sendback_string = "&_&";
    for ($i = 0; $i < $category_length; $i++) {
        if ($search_categories_array[$i] == "districts") {
            $sendback_string = $sendback_string."district:!:";
            //search function for districts
            $stmt = $conn->prepare("SELECT district_id FROM districts WHERE lower(district_name)=?");
            $stmt->bind_param("s",$search_term);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                
            }
        }
    }
    //sendback 
    //"district:!:name#-#link~-~name#-#link&_&"
?>