<?php
session_start();
if(!isset($_GET["meme"])) {
    header("location: /");
}

require ("dbConn.php");
spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});
use \Michelf\Markdown;
use \Michelf\MarkdownExtra;
                
$stmt = $conn->prepare("SELECT Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Meme.deletado, Usuario.login, Usuario.doots, count(Comentario.id), MemeDoot.updoot FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN MemeDoot ON (Meme.id = MemeDoot.idMeme AND MemeDoot.idUsuario = ?) LEFT JOIN Comentario ON Meme.id = Comentario.idMeme WHERE Meme.id = ? LIMIT 1");
$stmt->bind_param("ss", $_SESSION["id"], $_GET["meme"]);
$stmt->execute();
            
$stmt->bind_result($titulo, $arquivo, $doots, $datahora, $deletado, $loginUsuario, $dootsUsuario, $countComentarios, $updoot);
$stmt->fetch();
$stmt->close();

$colorup = "black";
$colordown = "black";
$unup = "";
$undown = "";

if($updoot == "1") {
    $colorup = "#e600e6";
    $colordown = "black";
    $unup = "un_";
    $undown = "";
}
else if($updoot == "0") {
    $colorup = "black";
    $colordown = "#e600e6";
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
                <?php
                    if (isset($_SESSION["login"])) {
                        echo '
                            var memeID = btn.value;

                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.open("POST", "updoot.php", true);
                            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xmlhttp.send("memeID=" + memeID);

                            // Depois de fazer um updoot, tem que mudar o botão.
                            btn.setAttribute("style", "color:#e600e6;");
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
                            }';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            function downdoot(btn) {
                <?php
                    if (isset($_SESSION["login"])) {
                        echo '
                            var memeID = btn.value;

                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.open("POST", "downdoot.php", true);
                            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xmlhttp.send("memeID=" + memeID);

                            // Depois de fazer um updoot, tem que mudar o botão.
                            btn.setAttribute("style", "color:#e600e6;");
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
                            }';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            
            <?php 
                if (isset($_SESSION["login"])) {
                    echo '
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
                        }';
                }
            ?>
        </script>
        <script>
            function updoot_comment(btn) {
                <?php 
                    if (isset($_SESSION["login"])) {
                        echo '
                            var commentID = btn.value;

                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.open("POST", "updoot_comment.php", true);
                            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xmlhttp.send("commentID=" + commentID);

                            // Depois de fazer um updoot, tem que mudar o botão.
                            btn.setAttribute("style", "color:#e600e6;");
                            btn.setAttribute("onclick", "un_updoot_comment(this);");

                            // Altera o texto da pontuação.
                            if(document.getElementById("downcommentbtn" + btn.value).getAttribute("onclick") == "un_downdoot_comment(this);") {
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) + 2;

                                // Também tem que resetar o outro botão.
                                document.getElementById("downcommentbtn" + btn.value).setAttribute("style", "color:black;");
                                document.getElementById("downcommentbtn" + btn.value).setAttribute("onclick", "downdoot_comment(this);");
                            }
                            else {
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) + 1;
                            }';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            function downdoot_comment(btn) {
                <?php 
                    if (isset($_SESSION["login"])) {
                        echo '
                            var commentID = btn.value;

                            var xmlhttp = new XMLHttpRequest();
                            xmlhttp.open("POST", "downdoot_comment.php", true);
                            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xmlhttp.send("commentID=" + commentID);

                            // Depois de fazer um updoot, tem que mudar o botão.
                            btn.setAttribute("style", "color:#e600e6;");
                            btn.setAttribute("onclick", "un_downdoot_comment(this);");

                            // Altera o texto da pontuação.
                            if(document.getElementById("upcommentbtn" + btn.value).getAttribute("onclick") == "un_updoot_comment(this);") {
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) - 2;

                                // Também tem que resetar o outro botão.
                                document.getElementById("upcommentbtn" + btn.value).setAttribute("style", "color:black;");
                                document.getElementById("upcommentbtn" + btn.value).setAttribute("onclick", "updoot_comment(this);");
                            }
                            else {
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) - 1;
                            }';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            <?php 
                    if (isset($_SESSION["login"])) {
                        echo '
                            function un_updoot_comment(btn) {
                                var commentID = btn.value;

                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.open("POST", "un_updoot_comment.php", true);
                                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xmlhttp.send("commentID=" + commentID);

                                // Depois de fazer um un_updoot, tem que mudar o botão.
                                btn.setAttribute("style", "color:black;");
                                btn.setAttribute("onclick", "updoot_comment(this);");

                                // Altera o texto da pontuação.
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) - 1;
                            }
                            function un_downdoot_comment(btn) {
                                var commentID = btn.value;

                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.open("POST", "un_downdoot_comment.php", true);
                                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xmlhttp.send("commentID=" + commentID);

                                // Depois de fazer um un_downdoot, tem que mudar o botão.
                                btn.setAttribute("style", "color:black;");
                                btn.setAttribute("onclick", "downdoot_comment(this);");

                                // Altera o texto da pontuação.
                                document.getElementById("dootscomment" + btn.value).innerHTML = parseInt(document.getElementById("dootscomment" + btn.value).innerHTML) + 1;
                            }';
                    }
            ?>
        </script>
        <script>
            function delete_comment(btn) {
                var commentID = btn.value;
                
                if(confirm("Deseja realmente excluir este comentário?")) {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "delete_comment.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("commentID=" + commentID);
                    
                    document.getElementById("comentario" + btn.value).innerHTML = "<div class='w3-left' style='margin-right:10px;width:45.94px;height:98px;'></div><div class='comment-author w3-left' style='margin-right:10px;'><img class='avatar w3-round' src='avatares/avatar.png'><p>[Excluído]</p><p class='w3-tiny'> 00:00 00/00/0000 </p></div><div style='overflow:hidden;'>[Excluído]</div>";
                }
            }
            function show_edit_comment(btn) {
                var commentID = btn.value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("textocomentario" + btn.value).innerHTML = "<textarea style='width:800px;height:100px;resize:none;' id='editcomentario" + btn.value + "'>" + this.responseText + "</textarea><br><button onclick='edit_comment(this);' value='" + btn.value + "'>Enviar</button>";
                    }
                }
                xmlhttp.open("POST", "getCommentText.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("commentID=" + commentID);
            }
            function edit_comment(btn) {
                var commentID = btn.value;
                var commentText = document.getElementById("editcomentario" + btn.value).value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        document.getElementById("textocomentario" + btn.value).innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("POST", "edit_comment.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("commentID=" + commentID + "&commentText=" + commentText);
            }
        </script>
        <script>
            function delete_meme(btn) {
                if(confirm("Deseja realmente excluir este meme?")) {
                    document.getElementById("deletememe").submit();
                }
            }
        </script>
        <script>
            function show_reaction_menu() {
                <?php 
                    if (isset($_SESSION["login"])) {
                        echo 'if(document.getElementById("reaction-menu").getAttribute("style") == "display:none;") {'.
                                'document.getElementById("reaction-menu").setAttribute("style", "display:block;");'.
                            '}'. 
                            'else if(document.getElementById("reaction-menu").getAttribute("style") == "display:block;") {'.
                                'document.getElementById("reaction-menu").setAttribute("style", "display:none;");'.
                            '}';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            function react(btn) {
                <?php 
                    if (isset($_SESSION["login"])) {
                        echo "var reacao = btn.value;".
                
                            "var xmlhttp = new XMLHttpRequest();".
                            "xmlhttp.open('POST', 'processReaction.php', true);".
                            "xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');".
                            "xmlhttp.send('reacao=' + btn.value + '&meme={$_GET["meme"]}');".

                            "if(document.getElementById('reacao' + reacao).getAttribute('onclick') == 'react(this);') {".
                                "document.getElementById('reacao' + reacao).setAttribute('style', 'display:inline;background-color:#e600e6;');".
                                "document.getElementById('reacao' + reacao).setAttribute('onclick', 'un_react(this);');".
                                "document.getElementById('pontosreacao' + reacao).innerHTML = parseInt(document.getElementById('pontosreacao' + reacao).innerHTML) + 1;".
                            "}";
                            echo 'if(document.getElementById("reaction-menu").getAttribute("style") == "display:block;") {'.
                                'document.getElementById("reaction-menu").setAttribute("style", "display:none;");'.
                            '}';
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
            function un_react(btn) {
                <?php 
                    if (isset($_SESSION["login"])) {
                        echo "var reacao = btn.value;".
                
                            "var xmlhttp = new XMLHttpRequest();".
                            "xmlhttp.open('POST', 'processUndoReaction.php', true);".
                            "xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');".
                            "xmlhttp.send('reacao=' + btn.value + '&meme={$_GET["meme"]}');".

                            "if(document.getElementById('reacao' + reacao).getAttribute('onclick') == 'un_react(this);') {".
                                "var points = parseInt(document.getElementById('pontosreacao' + reacao).innerHTML) - 1;".
                                "document.getElementById('pontosreacao' + reacao).innerHTML = points;".
                                "if(points == 0) {".
                                    "document.getElementById('reacao' + reacao).setAttribute('style', 'display:none;background-color:purple;');".
                                "}".
                                "else {".
                                    "document.getElementById('reacao' + reacao).setAttribute('style', 'display:inline;background-color:purple;');".
                                "}".
                                "document.getElementById('reacao' + reacao).setAttribute('onclick', 'react(this);');".
                            "}";
                    }
                    else {
                        echo 'window.location = "registerLogin.php";';
                    }
                ?>
            }
        </script>
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div class="meme-wrapper w3-purple">
            <?php
                $deletebutton = "";
                $formdelete = "";
                if(isset($_SESSION["login"]) && $_SESSION["login"] == $loginUsuario) {
                    $deletebutton = "<button class='w3-button' style='padding:0px;margin-left:20px;' onclick='delete_meme(this);'><i class='fa fa-trash-o' aria-hidden='true'></i></button>";
                    $formdelete = "<form method='post' action='deleteMeme.php' id='deletememe'><input type='hidden' name='memeID' value='{$_GET["meme"]}'></form>";
                }
                
                if($arquivo != NULL && $deletado == false) {
                    echo "<div style='height:100px;'>" .
                            "<div class='w3-left' style='margin-right:2px;'>" .
                                "<p style='margin:0px;'><button class='w3-button' value='{$_GET["meme"]}' id='upbtn{$_GET["meme"]}' style='color:$colorup;' onclick='{$unup}updoot(this);'><i class='fa fa-arrow-up'></i></button></p>" .
                                "<p id='doots{$_GET["meme"]}' style='text-align:center;margin:0px;'>$doots</p>" .
                                "<p style='margin:0px;'><button class='w3-button' value='{$_GET["meme"]}' id='downbtn{$_GET["meme"]}' style='color:$colordown;' onclick='{$undown}downdoot(this);'><i class='fa fa-arrow-down'></i></button></p>" .
                            "</div>" .
                            "<div style='overflow:hidden;padding-top:20px;'>" .
                                "<h2 style='margin:0px;'><a class='link-branco' href='showMeme.php?meme={$_GET["meme"]}'>$titulo</a>$deletebutton</h2>" .
                                "<p style='margin:0px;'> Postado em " . date_format(date_create($datahora), "H:i d/m/Y") . " por <a class='link-branco' href='user.php?login=$loginUsuario'>$loginUsuario</a> ($dootsUsuario)</p>" .
                                $formdelete .
                            "</div>" .
                        "</div>" .
                        "<div class='meme-image'><img src='memes/$arquivo'></div>";
                    
                    echo "<div style='margin:12px;'>";
                    
                    $stmt = $conn->prepare("SELECT Reacao.id, Reacao.label, Reacao.imagem, count(MR1.idReacao), MR2.idUsuario FROM Reacao LEFT JOIN MemeReacao AS MR1 ON (Reacao.id = MR1.idReacao AND MR1.idMeme = ?) LEFT JOIN MemeReacao AS MR2 ON (MR2.idMeme = ? AND MR2.idUsuario=? AND MR2.idReacao = MR1.idReacao) GROUP BY Reacao.id");
                    $stmt->bind_param("sss", $_GET["meme"], $_GET["meme"], $_SESSION["id"]);
                    $stmt->execute();
                    $stmt->bind_result($reacaoID, $reacaoLabel, $reacaoImagem, $reacaoCount, $reacaoUsuario);
                    
                    $reacaoLabels = array();
                    $reacaoImagens = array();
                    
                    echo "<p>";
                    while($stmt->fetch()) {
                        $unreact = "";
                        $color = "purple";
                        if($reacaoUsuario) {
                            $unreact = "un_";
                            $color = "#e600e6";
                        }
                        
                        if($reacaoCount != 0) {
                            echo "<button class='w3-button' id='reacao$reacaoID' value='$reacaoID' onclick='{$unreact}react(this);' style='display:inline;background-color:$color;'><img class='reaction-img' src='imagens/$reacaoImagem'> <span id='pontosreacao$reacaoID'>$reacaoCount</span></button>";
                        }
                        else {
                            echo "<button class='w3-button' id='reacao$reacaoID' value='$reacaoID' onclick='{$unreact}react(this);' style='display:none;background-color:$color;'><img class='reaction-img' src='imagens/$reacaoImagem'> <span id='pontosreacao$reacaoID'>$reacaoCount</span></button>";
                        }
                        $reacaoLabels[$reacaoID] = $reacaoLabel;
                        $reacaoImagens[$reacaoID] = $reacaoImagem;
                    }
                    echo "</p>";
                    
                    $stmt->close();
                    
                    echo    "<div class='w3-right'><button class='w3-button w3-right reaction-btn' onclick='show_reaction_menu();'><span><i class='fa fa-smile-o'></span></i></button>" .
                            "<div id='reaction-menu' style='display:none;'>";
                    
                    foreach($reacaoLabels as $r_id => $r_label) {
                        echo "<button class='w3-button' value='$r_id' onclick='react(this);'><img src='imagens/{$reacaoImagens[$r_id]}'></button>";
                    }
                    
                    echo "</div></div>" .
                        "<h3 id='comentarios'>Comentários</h3>" .
                        "<div>" .
                            "<form method='post' action='processComment.php'>" .
                            "<textarea name='comentario' style='width:100%;height:100px;resize:none;' placeholder='Escreva um comentário aqui!'></textarea>" .
                            "<input type='hidden' name='meme' value='{$_GET["meme"]}'>" .
                            "<input type='submit'>" .
                            "</form>" .
                        "</div>";
                    
                    //echo "<div style='margin:12px;'></div>";
                    
                    if($countComentarios > 0) {
                        $stmt = $conn->prepare("SELECT Comentario.id, Comentario.conteudo, Comentario.doots, Comentario.dataHora, Comentario.editado, Comentario.deletado, Comentario.dataHoraEdit, Usuario.login, Usuario.doots, Usuario.avatar, ComentarioDoot.updoot FROM Comentario INNER JOIN Usuario ON Comentario.idUsuario = Usuario.id LEFT JOIN ComentarioDoot ON (Comentario.id = ComentarioDoot.idComentario AND ComentarioDoot.idUsuario = ?) WHERE Comentario.idMeme = ? ORDER BY Comentario.doots DESC, Comentario.dataHora DESC");
                        $stmt->bind_param("ss", $_SESSION["id"], $_GET["meme"]);
                        $stmt->execute();
                        
                        echo "<ul class='w3-ul' style='margin-top:10px;'>";
                        
                        $stmt->bind_result($idComentario, $textoComentario, $dootsComentario, $datahoraComentario, $editadoComentario, $deletadoComentario, $datahoraeditComentario, $loginUsuarioComentario, $dootsUsuarioComentario, $avatarUsuarioComentario, $updootComentario);
                        while($stmt->fetch()) {
                            $colorupcomentario = "black";
                            $colordowncomentario = "black";
                            $unupcomentario = "";
                            $undowncomentario = "";

                            if($updootComentario == "1") {
                                $colorupcomentario = "#e600e6";
                                $colordowncomentario = "black";
                                $unupcomentario = "un_";
                                $undowncomentario = "";
                            }
                            else if($updootComentario == "0") {
                                $colorupcomentario = "black";
                                $colordowncomentario = "#e600e6";
                                $unupcomentario = "";
                                $undowncomentario = "un_";
                            }
                            
                            if(!$deletadoComentario) {
                                $textoComentarioMarkdown = Markdown::defaultTransform($textoComentario);
                                echo "<li class='comment-wrapper' style='min-height:114px;' id='comentario$idComentario'>" .
                                        "<div class='w3-left' style='margin-right:2px;'>" .
                                            "<p style='margin:0px;'><button class='w3-button' value='$idComentario' id='upcommentbtn$idComentario' style='color:{$colorupcomentario};' onclick='{$unupcomentario}updoot_comment(this);'><i class='fa fa-arrow-up'></i></button></p>" .
                                            "<p id='dootscomment$idComentario' style='text-align:center;margin:0px;'>$dootsComentario</p>" .
                                            "<p style='margin:0px;'><button class='w3-button' value='$idComentario' id='downcommentbtn$idComentario' style='color:{$colordowncomentario};' onclick='{$undowncomentario}downdoot_comment(this);'><i class='fa fa-arrow-down'></i></button></p>" .
                                        "</div>" .
                                        "<div class='comment-author w3-left' style='margin-right:10px;'>" .
                                            "<a href='user.php?login=$loginUsuarioComentario'><img class='avatar w3-round' src='avatares/$avatarUsuarioComentario'></a>" .
                                            "<p><a href='user.php?login=$loginUsuarioComentario'>$loginUsuarioComentario</a> ($dootsUsuarioComentario)</p>" .
                                            "<p class='w3-tiny'>" . date_format(date_create($datahoraComentario), "H:i d/m/Y") . "</p>";
                                if($editadoComentario == true) {
                                    echo    "<p class='w3-tiny'>Editado em: </p>" .
                                            "<p class='w3-tiny'>" . date_format(date_create($datahoraeditComentario), "H:i d/m/Y") . "</p>";
                                }
                                if(isset($_SESSION["login"]) && $loginUsuarioComentario == $_SESSION['login']) {
                                    echo    "<p><button class='w3-small w3-button text-btn' style='padding:0px;' value='$idComentario' onclick='show_edit_comment(this);'><i class='fa fa-pencil' aria-hidden='true'> Editar</i></button></p>" .
                                            "<p><button class='w3-small w3-button text-btn' style='padding:0px;' value='$idComentario' onclick='delete_comment(this);'><i class='fa fa-trash-o' aria-hidden='true'> Excluir</i></button></p>";
                                }
                                echo        "</div>" .
                                        "<div style='overflow:hidden;' id='textocomentario$idComentario'>$textoComentarioMarkdown</div>" .
                                    "</li>";
                            }
                            else {
                                echo "<li class='comment-wrapper' style='min-height:114px;'>" .
                                        "<div class='w3-left' style='margin-right:10px;width:45.94px;height:98px;'>" .
                                        "</div>" .
                                        "<div class='comment-author w3-left' style='margin-right:10px;'>" .
                                            "<img class='avatar w3-round' src='avatares/avatar.png'>" .
                                            "<p>[Excluído]</p>" .
                                            "<p class='w3-tiny'> 00:00 00/00/0000 </p>" .
                                            "</div>" .
                                        "<div style='overflow:hidden;'>[Excluído]</div>" .
                                    "</li>";
                            }
                        }
                        
                        echo "</ul>";
                        
                        $stmt->close();
                    }
                    else {
                        echo '<h4 class="comment-wrapper">Ainda não há comentários. Escreva um você! <b>AGORA!</b></h4>';
                    }
                    
                    echo '</div>';
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
