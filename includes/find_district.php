<?php
    //the variables that needed to be passed: $coord = ("12,34"). Returned variable $district_location = "district"
    $probability_district_array = array();
    $stmt = $conn->prepare("SELECT district_name,points FROM districts");
    $stmt->execute();
    $result = $stmt->get_result();
    $coord = explode(",",$coord);
    $coord = [intval($coord[0]),intval($coord[1])];
    while ($row = $result->fetch_assoc()) {
        $polygon = explode(".",$row["points"]);
        $n = count($polygon);
        $is_in = "no";
        $x = $coord[0];
        $y = $coord[1];

        for ($i=0; $i < $n; ++$i) {
            $current_coord = explode(",",$polygon[$i]);
            $current_coord = [intval($current_coord[0]),intval($current_coord[1])];
            if ($i < count($polygon)){
                $next_coord = explode(",",$polygon[$i+1]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];
            }
            else {
                $next_coord = explode (",",$polygon[0]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];
            }
            $x1 = $current_coord[0];
            $x2 = $next_coord[0];
            $y1 = $current_coord[1];
            $y2 = $current_coord[1];
            error_log ("Ran check for line in ".$row["district_name"]);

            if ($y < $y1 != $y < $y2 && $x < ($x2-$x1) * ($y-$y1) / ($y2-$y1) + $x1) {
                if ($is_in == "no") {
                    $is_in = "yes";
                }
                else {
                    $is_in = "no";
                }
            }
        }
        array_push ($probability_district_array, $row["district_name"]." result: ".$is_in."<br>");
    }
?>
