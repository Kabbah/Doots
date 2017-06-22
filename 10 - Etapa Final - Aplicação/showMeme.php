<?php
session_start();
if(!isset($_GET["meme"])) {
    header("location:logado.php");
}

require ("dbConn.php");
                
$stmt = $conn->prepare("SELECT Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Meme.deletado, Usuario.login, count(Comentario.id), MemeDoot.updoot FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN MemeDoot ON (Meme.id = MemeDoot.idMeme AND MemeDoot.idUsuario = ?) LEFT JOIN Comentario ON Meme.id = Comentario.idMeme WHERE Meme.id = ? LIMIT 1");
$stmt->bind_param("ss", $_SESSION["id"], $_GET["meme"]);
$stmt->execute();
            
$stmt->bind_result($titulo, $arquivo, $doots, $datahora, $deletado, $loginUsuario, $countComentarios, $updoot);
$stmt->fetch();
$stmt->close();

$colorup = "black";
$colordown = "black";
$unup = "";
$undown = "";

if($updoot == "1") {
    $colorup = "purple";
    $colordown = "black";
    $unup = "un_";
    $undown = "";
}
else if($updoot == "0") {
    $colorup = "black";
    $colordown = "purple";
    $unup = "";
    $undown = "un_";
}

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
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        <script>
            function updoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "updoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um updoot, tem que mudar o botão.
                btn.setAttribute("style", "color:purple;");
                btn.setAttribute("onclick", "un_updoot(this);");
                
                // Altera o texto da pontuação.
                if(document.getElementById("downbtn" + btn.value).getAttribute("onclick") == "un_downdoot(this);") {
                    document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) + 2;
                    
                    // Também tem que resetar o outro botão.
                    document.getElementById("downbtn" + btn.value).setAttribute("style", "color:black;");
                    document.getElementById("downbtn" + btn.value).setAttribute("onclick", "downdoot(this);");
                }
                else {
                    document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) + 1;
                }
            }
            function downdoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "downdoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um updoot, tem que mudar o botão.
                btn.setAttribute("style", "color:purple;");
                btn.setAttribute("onclick", "un_downdoot(this);");
                
                // Altera o texto da pontuação.
                if(document.getElementById("upbtn" + btn.value).getAttribute("onclick") == "un_updoot(this);") {
                    document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) - 2;
                    
                    // Também tem que resetar o outro botão.
                    document.getElementById("upbtn" + btn.value).setAttribute("style", "color:black;");
                    document.getElementById("upbtn" + btn.value).setAttribute("onclick", "updoot(this);");
                }
                else {
                    document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) - 1;
                }
            }
            function un_updoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "un_updoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um un_updoot, tem que mudar o botão.
                btn.setAttribute("style", "color:black;");
                btn.setAttribute("onclick", "updoot(this);");
                
                // Altera o texto da pontuação.
                document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) - 1;
            }
            function un_downdoot(btn) {
                var memeID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "un_downdoot.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("memeID=" + memeID);
                
                // Depois de fazer um un_downdoot, tem que mudar o botão.
                btn.setAttribute("style", "color:black;");
                btn.setAttribute("onclick", "downdoot(this);");
                
                // Altera o texto da pontuação.
                document.getElementById("doots" + btn.value).innerHTML = parseInt(document.getElementById("doots" + btn.value).innerHTML) + 1;
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
                    echo "<div style='height:100px;'>" .
                            "<div class='w3-left' style='margin-right:10px;'>" .
                                "<p style='margin:0px;'><button class='w3-button' value='{$_GET["meme"]}' id='upbtn{$_GET["meme"]}' style='color:$colorup;' onclick='{$unup}updoot(this);'><i class='fa fa-arrow-up'></i></button></p>" .
                                "<p id='doots{$_GET["meme"]}' style='text-align:center;margin:0px;'>$doots</p>" .
                                "<p style='margin:0px;'><button class='w3-button' value='{$_GET["meme"]}' id='downbtn{$_GET["meme"]}' style='color:$colordown;' onclick='{$undown}downdoot(this);'><i class='fa fa-arrow-down'></i></button></p>" .
                            "</div>" .
                            "<div style='overflow:hidden;padding-top:20px;'>" .
                                "<h2 style='margin:0px;'><a href='showMeme.php?meme={$_GET["meme"]}'>$titulo</a></h2>" .
                                "<p style='margin:0px;'> Postado em " . date_format(date_create($datahora), "H:i d/m/Y") . " por $loginUsuario</p>" .
                            "</div>" .
                        "</div>" .
                        "<div class='meme-image'><img src='memes/$arquivo'></div>";
                    
                    echo "<div></div>"; // Reservado pra input de comentário
                    
                    if($countComentarios > 0) {
                        $stmt = $conn->prepare("SELECT Comentario.conteudo, Comentario.doots, Comentario.dataHora, Comentario.editado, Comentario.deletado, Comentario.dataHoraEdit, Usuario.login, Usuario.avatar FROM Comentario INNER JOIN Usuario ON Comentario.idUsuario = Usuario.id WHERE Comentario.idMeme = ?");
                        $stmt->bind_param("s", $_GET["meme"]);
                        $stmt->execute();
                        
                        echo "<ul class='w3-ul' style='margin-top:10px;'>";
                        
                        $stmt->bind_result($textoComentario, $dootsComentario, $datahoraComentario, $editadoComentario, $deletadoComentario, $datahoraeditComentario, $loginUsuarioComentario, $avatarUsuarioComentario);
                        while($stmt->fetch()) {
                            echo "<li class='comment-wrapper' style='min-height:114px;'>" .
                                    "<div class='comment-author w3-left' style='margin-right:10px;'>" .
                                        "<img class='avatar' src='avatares/$avatarUsuarioComentario'>" .
                                        "<p style='margin:0px;'>$loginUsuarioComentario</p>" .
                                        "<p class='w3-tiny' style='margin:0px;'>" . date_format(date_create($datahoraComentario), "H:i d/m/Y") . "</p>" .
                                    "</div>" .
                                    "<p style='overflow:hidden;'>$textoComentario</p>" .
                                "</li>";
                        }
                        
                        echo "</ul>";
                        
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
