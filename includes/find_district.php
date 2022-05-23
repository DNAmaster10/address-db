<?php
    function find_x($y, $x1, $y1, $x2, $y2) {
        $dx = $x1 - $x2;
        $dy = $y1 - $y2;
        if ($dx < 0) {
            $dx = $dx * -1;
        }
        if ($dy < 0) {
            $dy = $dy * -1;
        }
        if ($dx == 0) {
            return ($x1);
        }
        if ($dy == 0) {
            return ("unknown");
        }
        $dxdy = $dx / $dy;
        $pdy1 = $y1 - $y;
        return ($x1 + ($dxdy * $pdy1));
    }

    function check_line($x, $y, $x1, $y1, $x2, $y2) {
        if (($y1 > $y2 && $y < $y1 && $y > $y2) or ($y1 < $y2 && $y > $y1 && $y < $y2)) {
            $xpoint = find_x($x, $x1, $y1, $x2, $y2);
            if ($xpoint >= $x) {
                return (true);
            }
            else {
                return (false);
            }
        }
        else {
            return (false);
        }
    }
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
        $is_in = false;
        $x = $coord[0];
        $y = $coord[1];

        for ($i=0; $i < $n; $i++) {
            error_log("Checking line in ".$row["district_name"]);
            $collisions = 0;
            $current_coord = explode(",",$polygon[$i]);
            $current_coord = [intval($current_coord[0]),intval($current_coord[1])];
            if ($i < $n){
                $next_coord = explode (",",$polygon[$i + 1]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];

            }
            else {
                $next_coord = explode(",",$polygon[0]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];
            }
            $x1 = $current_coord[0];
            $x2 = $next_coord[0];
            $y1 = $current_coord[1] + 0.001;
            $y2 = $next_coord[1] + 0.001;
            $collides = check_line($x, $y, $x1, $y1, $x2, $y2);
            if ($collides) {
                error_log("Colision found in ".$row["district_name"]);
                $is_in = !$is_in;
            }
        }
        array_push ($probability_district_array, [$row["district_name"],$is_in]);
        error_log($row["district_name"]."State: ".strval($is_in)." Count: ".strval($collisions));
    }
    $n = count($probability_district_array);
    $found = false;
    $potential_districts = array();
    for ($i = 0; $i < $n; $i++) {
        if ($probability_district_array[$i][1]) {
            array_push($potential_districts, $probability_district_array[$i][0]);
            $found = true;
        }
    }
    if (!$found) {
        $district = "error1";
    }
    else {
        $district = $potential_districts[0][0];
    }
?>
