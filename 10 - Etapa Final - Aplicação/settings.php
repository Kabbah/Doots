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
        <?php
            if (isset($_COOKIE["erroEmail"])){
                echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroEmail'] . '</b></p>';
            }
            if (isset($_COOKIE["alteracaoErro"])){
                echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['alteracaoErro'] . '</b></p>';
            }
            if (isset($_COOKIE["alteracaoSucesso"])){
                echo '<div class="error"><p class="w3-panel w3-green"><b>' . $_COOKIE['alteracaoSucesso'] . '</b></p>';
            }
            if (isset($_COOKIE["erroPassword"])){
                echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroPassword'] . '</b></p>';
            }
            if (isset($_COOKIE["erroUpload"])){
                echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroUpload'] . '</b></p>';
            }
            if (isset($_COOKIE["avatarVazio"])){
                echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['avatarVazio'] . '</b></p>';
            }
        ?>
        <form method="post" enctype="multipart/form-data" action="processAvatar.php">
            <label>Avatar</label>
            <input type="file" name="avatar" required>
            <input type="submit">
        </form>
        <form method="post" action="processEmail.php">
            <label>Email</label>
            <input type="text" name="email" required>
            <label>Confirmar Email</label>
            <input type="text" name="email-confirm" required>
            <input type="submit">
        </form>
        <form method="post" action="processPassword.php">
            <label>Senha</label>
            <input type="password" name="password" required>
            <label>Confirmar Senha</label>
            <input type="password" name="password-confirm" required>
            <label>Senha Atual</label>
            <input type="password" name="password-now" required>
            <input type="submit">
        </form>
    </body>
</html>