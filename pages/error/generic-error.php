<?php
    session_start();
    if (isset($_SESSION["generic_error"])) {
        $error_message = $_SESSION["generic_error"];
    }
    else {
        $error_message = "An error occured";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/main.css">
        <title>Error</title>
    </head>
    <body>
        <ul class="navbar_container_ul" id="navbar_container">
            <li id="home_button_li" class="left"><a href="/index.php" class="navbar_button">Home</a></li>
            <li id="browse_button_li" class="left"><a href="/pages/browse.php" class="navbar_button">Browse</a></li>
            <?php
                if (isset($_SESSION["username"])) {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/logout.php' class='navbar_button'>Logout</a></li>";
                    echo "<li id='tools_button_li' class='right'><a href='/pages/user/user-home.php' class='navbar_button'>Tools</a></li>";
                }
                else {
                    echo "<li id='login_button_li' class='right'><a href='/pages/login/login.php' class='navbar_button'>Login</a></li>";
                }
            ?>
        </ul>
        <p><?php echo $error_message; ?></p>
        <form action="/index.php">
            <input type="submit" value="Home">
        </form>
    </body>
</html>
