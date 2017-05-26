<?php
    $host = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "Doots";

    $conn = new mysqli($host, $dbUser, $dbPass, $dbName);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());     
        exit();
    }
?>
