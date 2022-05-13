<?php
    //the variables that needed to be passed: $coord = ("12,34"). Returned variable $district_location = "district"
    $probability_district_array = array();
    $stmt = $conn->prepare("SELECT district_name,postcodeChar,points FROM districts");
    $stmt->execute();
    $result = $stmt->get_result();
    $coord = explode(",",$coord);
    $coord = [intval($coord[0]),intval($coord[1])];
    while ($row = $result->fetch_assoc()) {
        $polygon = explode(".",$row["points"]);
        $n = count($polygon);
        $is_in = false;
        $x = $coord[0];
        $y = $coord[1];

        for ($i=0; $i < $n-1; ++$i) {
            $current_coord = explode(",",$polygon[$i]);
            $current_coord = [intval($current_coord[0]),intval($current_coord[1])];
            if (!$i < count($polygon)){
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

            if ($y < $y1 != $y < $y2 && $x < ($x2-$x1) * ($y-$y1) / ($y2-$y1) + $x1) {
                if ($is_in) {
                    $is_in = false;
                }
                else {
                    $is_in = true;
                }
            }
            array_push ($probability_district_array, $row["district_name"]." result: ".strval($is_in)."<br>");
        }
    }
?>
