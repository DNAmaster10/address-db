<?php
  //Alter this file to suite the needs of your own database
  $dbServername = "localhost";
  $dbUsername = getenv("DB_USERNAME");
  $dbPassword = getenv("DB_PASSWORD");
  $dbName = getenv("DB_NAME");
  $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQLi: " . mysqli_connect_error();
  }
?>
