<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
    include $_SERVER["DOCUMENT_ROOT"]."/includes/check_login.php";
    if (isset($_SESSION["building_error"])) {
        $error = $_SESSION["building_error"];
        unset($_SESSION["building_error"]);
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
            <button type="button" action="get_details()" value="Generate details"></button>
            <p>District: </p>
            <select name="district" id="district_select">
                <?php
                    $stmt = $conn->prepare("SELECT district_name FROM districts");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["district_name"]."' onchange='changeStreetUnits()'>".$row["district_name"]."</option>";
                    }
                ?>
            </select>
            <p>Street Unit: </p>
            <select name="street_unit" it="street_unit_select">
            </select>
        </form>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/pages/register/register-building.js"></script>
</html>
