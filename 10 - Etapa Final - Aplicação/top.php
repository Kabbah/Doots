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
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div class="w3-bar w3-purple">
            <a href="/" class="w3-bar-item w3-button w3-mobile">populares</a>
            <a href="new.php" class="w3-bar-item w3-button w3-mobile">novos</a>
            <a href="top.php" class="w3-bar-item w3-button w3-mobile" style="background-color:#812092;">no topo</a>
            <form class="w3-bar-item w3-right w3-mobile" style="padding: 0px;">
                <input type="text" class="w3-bar-item w3-input" style="background-color:#eac0f1;" placeholder="Buscar...">
                <button type="submit" class="w3-bar-item w3-button w3-right">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
        <div>
            <!-- Isso vai ser feio pra caramba, é só pra ficar meio navegável. -->
            <p><a href="createMeme.php">Criar um meme</a></p>
            <?php
            require ("dbConn.php");
            
            $stmt = $conn->prepare("SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id) FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN Comentario ON Meme.id = Comentario.idMeme GROUP BY Meme.id ORDER BY Meme.doots DESC");
            $stmt->execute();
            
            $stmt->bind_result($memeId, $titulo, $arquivo, $doots, $datahora, $login, $countComentarios);
            while($stmt->fetch()) { // Isso vai pegar todos os memes e mostrar na front page. Caso a gente queira limitar para um número máximo, é aqui que vai mudar.
                // Esses echo que eu to fazendo vão ser horrivelmente feios, não tô com muita paciência pra CSS agora.
                echo "<p>" .
                        "<a href='showMeme.php?meme=$memeId'><img src='memes/$arquivo' style='max-width:150px;max-height:150px'></a>" .
                        "<span>$titulo</span>" .
                    "</p>";
            }
            
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </body>
</html>
