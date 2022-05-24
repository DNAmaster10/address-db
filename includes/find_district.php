<?php
    function find_x($y, $x1, $y1, $x2, $y2) {
        $dy = $y1 - $y2;
        $dx = $x1 - $x2;
        $gradient = $dy / $dx;
        $yintercept = $y1 - ($gradient * $x1);
        $xpoint = ($y - $yintercept) / $gradient;
        return ($xpoint);
    }
    function check_line($x, $y, $x1, $y1, $x2, $y2) {
        if (($y1 >= $y2 && $y >= $y2 && $y <= $y1) or ($y2 >= $y1 && $y >= $y1 && $y <= $y2)) {
            if (($y1 == $y2 and $x1 > $x and $x2 < $x) or ($y1 == $y2 and $x2 > $x1 and $x2 > $x and $x1 < $x) or ($x < $x1 and $x < $x2)) {
                return(true);
            }
            else if ($x1 == $x2 and $x <= $x1) {
                return(true);
            }
            else if ($x1 == $x2 and $x > $x1) {
                return(false);
            }
            else {
                $xpoint = find_x($y, $x1, $y1, $x2, $y2);
                if ($xpoint > $x) {
                    return(true);
                }
                else {
                    return(false);
                }
            }
        }
        else {
            return (false);
        }
    }
    //the variables that needed to be passed: $coord = ("12,34"). Returned variable $district_location = "district"
    error_log("Running with coords ". strval($coords));
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
		$collisions = 0;
        for ($i=0; $i < $n; $i++) {
            $current_coord = explode(",",$polygon[$i]);
            $current_coord = [intval($current_coord[0]),intval($current_coord[1])];
            if ($i < $n - 1){
                $next_coord = explode (",",$polygon[$i + 1]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];

            }
            else {
                $next_coord = explode(",",$polygon[0]);
                $next_coord = [intval($next_coord[0]),intval($next_coord[1])];
            }
            $x1 = intval($current_coord[0]);
            $x2 = intval($next_coord[0]);
            $y1 = floatval($current_coord[1] + 0.001);
            $y2 = floatval($next_coord[1] + 0.001);
            $collides = check_line($x, $y, $x1, $y1, $x2, $y2);
            if ($collides) {
                $is_in = !$is_in;
                $collisions++;
            }
        }
        array_push ($probability_district_array, [$row["district_name"],$is_in]);
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
    unset ($coords);
?>
