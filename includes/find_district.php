<?php
    //the variables that needed to be passed: $coord = ("12,34"). Returned variable $district_location = "district"
    $probability_district_array = array();
    $stmt = $conn->prepare("SELECT district_name,postcodeChar,points FROM districts");
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
            $current_coord = explode(",",$polygon[$i]);
            if ($i+1 != $array_length){
                $next_coord = explode(",",$polygon[$i+1]);
            }
            else {
                $next_coord = explode(",",$polygon[0]);
            }
            $total_deltax = intval(intval($next_coord[0]) - intval($current_coord[0]));
            $total_deltay = intval(intval($next_coord[1]) - intval($current_coord[1]));
            if ($total_deltax >= 0) {
                $positive_total_delta_x = $total_deltax;
            }
            else {
                $positive_total_delta_x = $total_deltax * -1;
            }
            if ($total_deltay >= 0) {
                $positive_total_delta_y = $total_deltay;
            }
            else {
                $positive_total_delta_y = $total_deltay * -1;
            }
            if ($positive_total_delta_x >= $positive_total_delta_y) {
                $loop_ammount = $positive_total_delta_x;
                $delta_x = $total_deltax / $positive_total_delta_x;
                $delta_y = $total_deltay / $positive_total_delta_x;
            }
            else {
                $loop_ammount = $positive_total_delta_y;
                $delta_x = $total_deltax / $positive_total_delta_y;
                $delta_y = $total_deltay / $positive_total_delta_y;
            }
            $line_coords = [];
            array_push($line_coords, $polygon[$i]);
            $real_current_coords = [floatval($current_coord[0]), floatval($current_coord[1])];
            for ($j=0; $j < $loop_ammount - 1; $j++) {
                $real_current_coords[0] = $real_current_coords[0] + $delta_y;
                $real_current_coords[1] = $real_current_coords[1] + $delta_y;
                array_push ($line_coords, strval(round($real_current_coords[0])).",".strval(round($real_current_coords[1])));
            }
            $temp = explode($coord[0],",");
            $coord_current = [intval($temp[0]),intval($temp[1])];
            for ($h=0; $h < $width + 2) {
                $temps = (strval($coord_current[0]).",".strval($coord_current[1]));
                if (in_array($temps, $line_coords)) {
                    $intersections_x++;
                    break;
                }
                $coord_current[0] = $coord_current[0] + 1;
            }
            $coord_current = [intval($temp[0]),intval($temp[1])];
            for ($j=0; $j < $height + 2; $j++) {
                $temps = (strval($coord_current[0] + "," strval($coord_current[1]));
                if (in_array($temps, $line_coords)) {
                    $intersections_y++;
                    break;
                }
                $coord_current[1] = $coord_current[1] + 1
            }
            $coord_current = [intval($temp[0]),intval($temp[1])];
            for ($k=0; $k < $height + 2; $k++) {
                $temps = (strval($coord_current[0] + "," strval($coord_current[1]));
                if (in_array($temps, $line_coords)) {
                    $intersections_y_down++;
                    break;
                }
                $coord_current[1] = $coord_current[1] + 1;
            }
            $coord_current = [intval($temp[0]),intval($temp[1])];
            for ($l=0; $l < $width; $l++) {
                $temps = (strval($coord_current[0] + "," strval($coord_current[1]));
                if (in_array($temps, $line_coords)) {
                    $intersections_x_left++;
                    break;
                }
                $coord_current[0] = $coord_current[0] + 1;
            }
        }
        if (($intersections_x % 2) != 0) {
            $probability++;
        }
        if (($intersections_y % 2) != 0) {
            $probability++;
        }
        if (($intersections_x_left % 2) != 0) {
            $probability++;
        }
        if (($intersecions_y_down % 2) != 0) {
            $probability++;
        }
        array_push($probability_district_array, $row["district_name"].strval($probability))
    }
?>
