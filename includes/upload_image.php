<?php
    //takes input: $file, $file_name
    $image_result = "1";
    $target_dir = "uploads/images/";

    //Check if it's real or fake
    $check = getimagesize($file)

    //Register image in database
    $stmt = $conn->prepare("INSERT INTO images (name, file_name) VALUES (?, ?)");
    $stmt->bind_param("ss", "temp#-#", $file_name);
    $stmt->execute();
    $stmt->close();

    //Get the ID of the image
    $stmt = $conn->prepare("SELECT id FROM images WHERE file_name=? AND name=?");
    $stmt->bind_param("ss", $file_name, "temp#-#");
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!result) {
        unset($result);
        $image_result = "An error occured registering the image.";
    }
    else {
        
    }


    $targer_file = $target_dir.basename($file["name"]);

    $stmt = $conn->prepare("SELECT building_name FROM buildings WHERE parent_street=?");
    $stmt->bind_param("s",$street_name);
    $stmt->execute();
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    if (!$result) {
        unset($result);
    }

    $target_file = $
?>