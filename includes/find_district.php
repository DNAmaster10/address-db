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
            if ($temp < 0) {
                $temp = $temp * -1;
            }
            array_push($x_array, $temp);
        }

        $y_array = [];
        for ($i = 0; i < $array_length; $i++) {
            $temp = explode(",",$polygon[$i]);
            $temp = intval($temp[1]);
            if ($temp < 0) {
                $temp = $temp * -1;
            }
            array_push($y_array, $temp);
        }

        $x_min = min($x_array);
        $y_min = min($y_array);
        $x_max = max($x_array);
        $y_max = max($y_array);

    }
?>
