<?php
    //the variables that needed to be passed: $coord = ("12,34")
    $stmt = $conn->prepare("SELECT district_id,postcodeChar FROM districts")
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $polygon = explode("-",$row["points"]);
        $array_length = count($polygon);
        $x_array = [];
        for ($i = 0; $i < $array_length; $i++) {
            $temp = explode(",",$polygon[$i]);
            $temp = intval($temp[0]);
            array_push($x_array, $temp);
        }

        $y_array = [];
        for ($i = 0; i < $array_length; $i++) {
            $temp = explode(",",$polygon[$i]);
            $temp = intval($temp[1]);
            array_push($y_array, $temp);
        }

        $x_min = min($x_array);
        $y_min = min($y_array);
        $x_max = max($x_array);
        $y_max = max($y_array);
        $width = $x_max - $x_min;
        $height = $y_max - $y_min;
        
        $probability = 0;
        $intersections_x = 0;
        $intersections_y = 0;
        $intersections_x_left = 0;
        $intersections_y_down = 0;
        
        for ($i=0; $i < $array_length; $i++) {
            $current_coord = explode(",",$polygon[$i])
            if ($i+1 != $array_length){
                $next_coord = explode(",",$polygon[$i+1])
            }
            else {
                $next_coord = explode(",",$polygon[0]);
            }
            $total_deltax = intval(intval($next_coord[0]) - intval($current_coord[0]));
            $total_deltay = intval(intval($next_coord[1]) - intval($current_coord[1]));
            if 
        }
    }
?>
