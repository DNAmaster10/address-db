<?php
    include $_SERVER["DOCUMENT_ROOT"]."/includes/return_login.php";
    echo "<div id='header' class='header'>";
    echo '
    <ul class="navbar_container_ul" id="navbar_container">
        <li id="home_button_li" class="left"><a href="/index.php" class="navbar_button">Home</a></li>
        <li id="browse_button_li" class="left"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
    ';
    if ($logged_in) {
        echo "<li id='login_button_li' class='right'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
        echo "<li id='tools_button_li' class='right'><a href='/pages/user/user-home.php' class='navbar_button'>Tools</a></li>";
    }
    else {
        echo "<li id='login_button_li' class='right'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
    }
    echo "</ul>";
    echo "</div>";
?>