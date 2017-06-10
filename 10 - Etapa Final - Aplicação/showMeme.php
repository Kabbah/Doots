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
        <title><?php if($arquivo != NULL && $deletado == false)  { 
                            echo $titulo;
                        } 
                        else { 
                            echo "Meme não encontrado";
                        } 
            ?> - Doots</title>
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
                    
                    // Tem que colocar botão pra up/down doot do lado do título
                    echo '<p class="meme-title">' . $titulo . '</p>' .
                         '<span class="w3-small meme-time">Postado as ' . date_format(date_create($datahora), "H:i d/m/Y") . ' por ' . $loginUsuario . '</span>' .
                         '<div class="meme-image"><img src="memes/' . $arquivo . '"></div>' .
                         '<div></div>'; // Reservado pra input de comentário
                    
                    if($countComentarios > 0) {
                        $stmt = $conn->prepare("SELECT Comentario.conteudo, Comentario.doots, Comentario.dataHora, Comentario.editado, Comentario.deletado, Comentario.dataHoraEdit, Usuario.login, Usuario.avatar FROM Comentario INNER JOIN Usuario ON Comentario.idUsuario = Usuario.id WHERE Comentario.idMeme = ?");
                        $stmt->bind_param("s", $_GET["meme"]);
                        $stmt->execute();
                        
                        $stmt->bind_result($textoComentario, $dootsComentario, $datahoraComentario, $editadoComentario, $deletadoComentario, $datahoraeditComentario, $loginUsuarioComentario, $avatarUsuarioComentario);
                        while($stmt->fetch()) {
                            echo '<div class="comment-wrapper">
                                    <div class="comment-author w3-left">
                                        <img class="avatar" src="avatares/' . $avatarUsuarioComentario . '">
                                        <p>' . $loginUsuarioComentario . '</p>
                                        <p class="w3-tiny">' . date_format(date_create($datahoraComentario), "H:i d/m/Y") . '
                                    </div>
                                    <p>' . $textoComentario . '</p>
                                 </div>';

                                    
                        }
                        $stmt->close();
                    }
                    else {
                        echo '<h4 class="comment-wrapper">Ainda não há comentários. Escreva um você! <b>AGORA!</b></h4>';
                    }
                }
                else {
                    echo '<h1 class="meme-title">Hic Sunt Dracones</h1>';
                    echo '<h2 class="w3-container">Este meme não existe ou foi deletado</h2>';
                }
                
                $conn->close();
            ?>
        </div>
    </body>
</html>
