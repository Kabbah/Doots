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
        <div class="settings-wrapper">
            <?php
                if (isset($_COOKIE["erroEmail"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroEmail'] . '</b></p></div>';
                }
                if (isset($_COOKIE["alteracaoErro"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['alteracaoErro'] . '</b></p></div>';
                }
                if (isset($_COOKIE["alteracaoSucesso"])){
                    echo '<div class="error"><p class="w3-panel w3-green"><b>' . $_COOKIE['alteracaoSucesso'] . '</b></p></div>';
                }
                if (isset($_COOKIE["erroPassword"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroPassword'] . '</b></p></div>';
                }
                if (isset($_COOKIE["erroUpload"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroUpload'] . '</b></p></div>';
                }
                if (isset($_COOKIE["avatarVazio"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['avatarVazio'] . '</b></p></div>';
                }
            ?>
        
            <h3 class="w3-purple form-title">Mudar Avatar</h3>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-border-top w3-animate-opacity log-reg" method="post" enctype="multipart/form-data" action="processAvatar.php">
                <br/>
                <label class="w3-text-purple">Avatar</label>
                <input class="w3-input w3-border" type="file" name="avatar" required>

                <br/>
                <input class="w3-button w3-purple form-submit-button" type="submit">
            </form>

            <h3 class="w3-purple form-title">Mudar Email</h3>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" method="post" action="processEmail.php">
                <br/>
                <label class="w3-text-purple">Email</label>
                <input class="w3-input w3-border" type="text" name="email" required>

                <br/>
                <label class="w3-text-purple">Confirmar Email</label>
                <input class="w3-input w3-border" type="text" name="email-confirm" required>

                <br/>
                <input class="w3-button w3-purple form-submit-button" type="submit">
            </form>
            
            <h3 class="w3-purple form-title">Mudar Senha</h3>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" method="post" action="processPassword.php">
                <br/>
                <label class="w3-text-purple">Senha Atual</label>
                <input class="w3-input w3-border" type="password" name="password-now" required>

                <br/>
                <label class="w3-text-purple">Nova Senha</label>
                <input class="w3-input w3-border" type="password" name="password" required>

                <br/>
                <label class="w3-text-purple">Confirmar Senha</label>
                <input class="w3-input w3-border" type="password" name="password-confirm" required>

                <br/>
                <input class="w3-button w3-purple form-submit-button" type="submit">
            </form>
        </div>
    </body>
</html>