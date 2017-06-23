<?php
session_start();
if(!isset($_SESSION['login'])) {
    header("location:registerLogin.php");
}

require ("dbConn.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Opções - Doots</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <?php
            require('banner.php');
        ?>
        <form>
            <label>Avatar</label>
            <input type="file" name ="avatar">
            <input type='submit'>
        </form>
        <form>
            <label>Email</label>
            <input type="text" name="email">
            <label>Confirmar Email</label>
            <input type="text" name="email-confirm">
        </form>
        <form>
            <label>Senha</label>
            <input type="password" name="password">
            <label>Confirmar Senha</label>
            <input type="password" name="password-confirm">
        </form>
    </body>
</html>