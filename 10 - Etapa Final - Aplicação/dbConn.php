<?php
    define("HOST", "localhost");     // Para o host com o qual você quer se conectar.
    define("DBUSER", "root");    // O nome de usuário para o banco de dados. 
    define("DBPASS", "");    // A senha do banco de dados. 
    define("DBNAME", "Doots");    // O nome do banco de dados.

    $conn = new mysqli(HOST, DBUSER, DBPASS, DBNAME);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());     
        exit();
    }
?>
