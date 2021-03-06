<?php
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: registerLogin.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Postar um Meme - Doots</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    </head>
    
    <body>
        <?php
            require('banner.php');
        
            if (isset($_COOKIE["tituloVazio"])){
                 echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['tituloVazio'] . '</b></p></div>';
            }
            if (isset($_COOKIE["memeVazio"])){
                  echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['memeVazio'] . '</b></p></div>';
            }
        if (isset($_COOKIE["erroUpload"])){
                  echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['erroUpload'] . '</b></p></div>';
            }
        ?>
        <div class="create-wrapper">
            
            <h2 class="w3-purple form-title">Postar um Meme</h2>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" method="post" enctype="multipart/form-data" action="processMeme.php">
                <br/>
                <label class="w3-text-purple"><b>Título</b></label>
                <input class="w3-input w3-border" type="text" name="titulo" required>
                
                <br/>
                <label class="w3-text-purple"><b>Imagem</b></label>
                <input class="w3-input w3-border" type="file" name="imagem" required>
                
                <input class="w3-button w3-purple form-submit-button" type="submit" value="Enviar">
            </form>
        </div>
    </body>
</html>
