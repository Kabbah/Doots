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
        <script>
            function updoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() { // Para debug.
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("debug").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("POST", "updoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um updoot, tem que mudar o botão.
                btn.innerHTML = "Undo updoot";
                btn.setAttribute("onclick", "un_updoot(this);");
                
                // Também tem que resetar o outro botão.
                document.getElementById("downbtn" + btn.value).innerHTML = "Downdoot";
                document.getElementById("downbtn" + btn.value).setAttribute("onclick", "downdoot(this);");
            }
            function downdoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() { // Para debug.
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("debug").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("POST", "downdoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um downdoot, tem que mudar o botão.
                btn.innerHTML = "Undo downdoot";
                btn.setAttribute("onclick", "un_downdoot(this);");
                
                // Também tem que resetar o outro botão.
                document.getElementById("upbtn" + btn.value).innerHTML = "Updoot";
                document.getElementById("upbtn" + btn.value).setAttribute("onclick", "updoot(this);");
            }
            function un_updoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() { // Para debug.
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("debug").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("POST", "un_updoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um un_updoot, tem que mudar o botão.
                btn.innerHTML = "Updoot";
                btn.setAttribute("onclick", "updoot(this);");
            }
            function un_downdoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() { // Para debug.
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("debug").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("POST", "un_downdoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um un_downdoot, tem que mudar o botão.
                btn.innerHTML = "Downdoot";
                btn.setAttribute("onclick", "downdoot(this);");
            }
        </script>
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div class="meme-wrapper w3-purple">
            <?php
                
                if($arquivo != NULL && $deletado == false) {
                    
                    // Tem que colocar botão pra up/down doot do lado do título
                    echo '<button type="button" value="' . $_GET["meme"] . '" onclick="updoot(this);" id="upbtn' . $_GET["meme"] . '">Updoot</button>' .
                         '<button type="button" value="' . $_GET["meme"] . '" onclick="downdoot(this);" id="downbtn' . $_GET["meme"] . '">Downdoot</button>' .
                         '<p class="meme-title">' . $titulo . '</p>' .
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
                        echo '<p id="debug"></p>';
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
