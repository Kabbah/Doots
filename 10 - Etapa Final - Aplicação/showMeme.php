<?php
session_start();
if(!isset($_GET["meme"])) {
    header("location:logado.php");
}

require ("dbConn.php");
                
$stmt = $conn->prepare("SELECT Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Meme.deletado, Usuario.login, count(Comentario.id) FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN Comentario ON Meme.id = Comentario.idMeme WHERE Meme.id = ? LIMIT 1");
$stmt->bind_param("s", $_GET["meme"]);
$stmt->execute();
            
$stmt->bind_result($titulo, $arquivo, $doots, $datahora, $deletado, $loginUsuario, $countComentarios);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Postar um Meme - Doots</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div class="meme-wrapper w3-purple">
            <?php
                
                if($arquivo != NULL && $deletado == false) {
                    // Isso aqui vai estar feio para caramba, é só um protótipo ainda
                    
                    echo '<h2 class="meme-title">' . $titulo . '</h2>' .
                         '<div class="meme-image"><img src="memes/' . $arquivo . '"></div>';
                    
                    if($countComentarios > 0) {
                        $stmt = $conn->prepare("SELECT Comentario.conteudo, Comentario.doots, Comentario.dataHora, Comentario.editado, Comentario.deletado, Comentario.dataHoraEdit, Usuario.login FROM Comentario INNER JOIN Usuario ON Comentario.idUsuario = Usuario.id WHERE Comentario.idMeme = ?");
                        $stmt->bind_param("s", $_GET["meme"]);
                        $stmt->execute();
                        
                        $stmt->bind_result($textoComentario, $dootsComentario, $datahoraComentario, $editadoComentario, $deletadoComentario, $datahoraeditComentario, $loginUsuarioComentario);
                        while($stmt->fetch()) {
                            echo "<div>" .
                                    "<p>$textoComentario</p>" .
                                    "<p>$loginUsuarioComentario</p>" .
                                "</div>";
                        }
                        $stmt->close();
                    }
                    else {
                        echo "<p>Não há comentários.</p>";
                    }
                }
                else {
                    echo "<h1>Meme não encontrado</h1>";
                    echo "<p>Este meme não existe, ou foi deletado.</p>";
                }
                
                $conn->close();
            ?>
        </div>
    </body>
</html>
