<?php
    session_start();
    if (isset($_SESSION["login_error"])) {
        $error_message = $_SESSION["login_error"];
        unset ($_SESSION["login_error"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kaloro-db</title>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/login.css">
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
        <div id="login_container" class="login_container">
            <h1>Login</h1>
            <form action="/pages/login/login_submit.php" method="POST">
                <input type="text" name="username" placeholder="username">
                <input type="text" name="password" placeholder="password">
                <input type="submit" value="login">
            </form>
            <?php
                if (isset($error_message)) {
                    echo '<p>'.$error_message.'</p>';
                }
            ?>
        </div>
    </body>
</html>
