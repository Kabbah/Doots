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
            <!-- Isso vai ser feio pra caramba, é só pra ficar meio navegável. -->
            <p><a href="createMeme.php">Criar um meme</a></p>
            <?php
            require ("dbConn.php");
            // Fiz isso só para mostrar os memes do banco de dados (isso vai ficar mais complexo no futuro)
            $stmt = $conn->prepare("SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id) FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN Comentario ON Meme.id = Comentario.idMeme WHERE Meme.deletado = 0 GROUP BY Meme.id");
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
