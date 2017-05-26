<?php
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: registerLogin.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Login ou Registro</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    
    <body>
        <header class="w3-container w3-purple">
            <h1 class="w3-left">Você logou como <?php echo $_SESSION["login"];?></h1>
        </header>
    </body>
</html>
