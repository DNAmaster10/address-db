<?php
    session_start();
    include $_SERVER["DOCUMENT_ROOT"]."/includes/dbh.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/browse.css">
    </head>
    <body>
        <ul class="navbar_container_ul" id="navbar_container">
            <li id="home_button_li" class="left"><a href="/index.php" class="navbar_button">Home</a></li>
            <li id="browse_button_li" class="left"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='' class='left'><a href='/pages/register/register.php'";
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
                }
                else {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
                }
            ?>
        </ul>
        <div id="search_box_container">
            <p>Use this page to search for buildings, street units, streets and districts in Kaloro</p>
            <br>
            <input type="checkbox" id="search_all_checkbox" onclick="toggle_all()">
            <label for="search_all_checkbox">Search all</label>
            <input type="checkbox" id="search_district_checkbox" disabled>
            <label for="search_discrict_checkbox">Districts</label>
            <input type="checkbox" id="search_street_unit_checkbox" disabled>
            <label for="search_street_unit_checkbox">Street Units</label>
            <input type="checkbox" id="search_streets_checkbox" disabled>
            <label for="search_street_checkbox">Street names</label>
            <input type="checkbox" id="search_building_checkbox" disabled>
            <label for="search_building_checkbox">Buildings</label>
            <br>
            <input type="text" id="search_input_box" placeholder="search">
            <button type="button" id="submit_search_button" onclick="submit_search()">Search</button>
        </div>
        <p id="loading_text"></p>
        <div id="search_results_container">
            <div id="district_result_container" class="result_container">

            </div>
            <div id="street_units_result_container" class="result_container">

            </div>
            <div id="streets_result_container" class="result_container">

            </div>
            <div id="building_result_container" class="result_container">
                
            </div>
        </div>
        <script src="/pages/js/browse-main.js"></script>
    </body>
</html>
