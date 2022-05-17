<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/incldes/check_login.php";
    if (isset($_SESSION["building_error"])) {
        $error = $_SESSION["building_error"];
        unset($_SESSION["building_error"];
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register Building</title>
    </head>
    <body>
        <form action="register-building_submit.php" method="POST">
            <p>Co-ords. Format: "x,y" e.g: "10,-23". Pressing "Generate details" will attempt to autofill all inputs that can be found based on the buildings location.</p>
            <input type="text" name="coord" placeholder="x,y" required>
            <button type="button" action="get_details()" value="Generate details">
            <select name="district">
                <?php
                    $stmt = $conn->prepare("SELECT district_name FROM districts");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["district_name"]."'>".$row["district_name"]."</option>";
                    }
                ?>
            </select>
        </form>
    </body>
</html>
