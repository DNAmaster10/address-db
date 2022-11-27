<?php
  //Alter this file to suite the needs of your own database
  $dbServername = "localhost";
  $dbUsername = "dbUsername";
  $dbPassword = "dbPassword";
  $dbName = "kaloro-dbName";
  $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
  if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQLi: " . mysqli_connect_error();
    }
?>
