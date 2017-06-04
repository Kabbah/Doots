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
        <div>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" method="post" enctype="multipart/form-data" action="processMeme.php">
                <label class="w3-text-purple"><b>Título</b></label>
                <input class="w3-input w3-border" type="text" name="titulo" required>
                
                <label class="w3-text-purple"><b>Imagem</b></label>
                <input class="w3-input w3-border" type="file" name="imagem" required>
                
                <input class="w3-button w3-purple form-submit-button" type="submit" value="Enviar">
            </form>
        </div>
    </body>
</html>
