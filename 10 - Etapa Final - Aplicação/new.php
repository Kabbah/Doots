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
        <script>
            function openPreview(btn) {
                document.getElementById("preview" + btn.value).style.display = "block";
                btn.setAttribute("onclick", "closePreview(this)");
            }
            function closePreview(btn) {
                document.getElementById("preview" + btn.value).style.display = "none";
                btn.setAttribute("onclick", "openPreview(this)");
            }
        </script>
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
                
                // Depois de fazer um downdoot, tem que mudar o botão.
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
        <div class="w3-bar w3-purple">
            <a href="/" class="w3-bar-item w3-button w3-mobile">populares</a>
            <a href="new.php" class="w3-bar-item w3-button w3-mobile" style="background-color:#812092;">novos</a>
            <a href="top.php" class="w3-bar-item w3-button w3-mobile">no topo</a>
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
            
            $stmt = $conn->prepare("SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, count(Comentario.id), MemeDoot.updoot FROM Meme INNER JOIN Usuario ON Meme.poster = Usuario.id LEFT JOIN MemeDoot ON (Meme.id = MemeDoot.idMeme AND MemeDoot.idUsuario = ?) LEFT JOIN Comentario ON Meme.id = Comentario.idMeme GROUP BY Meme.id ORDER BY Meme.dataHora DESC");
            $stmt->bind_param("s", $_SESSION["id"]);
            $stmt->execute();
            
            echo "<ul class='w3-ul'>";
            
            $stmt->bind_result($memeId, $titulo, $arquivo, $doots, $datahora, $login, $countComentarios, $updoot);
            while($stmt->fetch()) {
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
                
                echo "<li class='w3-padding-16'>" .
                        "<div class='w3-left' style='margin-right:10px;'>" .
                            "<p style='margin:0px;'><button class='w3-button' value='$memeId' id='upbtn$memeId' style='color:$colorup;' onclick='{$unup}updoot(this);'><i class='fa fa-arrow-up'></i></button></p>" .
                            "<p id='doots$memeId' style='text-align:center;margin:0px;'>$doots</p>" .
                            "<p style='margin:0px;'><button class='w3-button' value='$memeId' id='downbtn$memeId' style='color:$colordown;' onclick='{$undown}downdoot(this);'><i class='fa fa-arrow-down'></i></button></p>" .
                        "</div>" .
                        "<div class='w3-left' style='margin-right:10px;'>" .
                            "<div>" .
                                "<a style='text-align:center;' href='showMeme.php?meme=$memeId'><img src='memes/$arquivo' style='width:80px;height:80px'></a>" .
                            "</div>" .
                        "</div>" .
                        "<div style='overflow:hidden;'>" .
                            "<h2 style='margin:0px;'><a href='showMeme.php?meme=$memeId'>$titulo</a></h2>" .
                            "<p style='margin:0px;'><button class='w3-button' value='$memeId' onclick='openPreview(this)'><i class='fa fa-image'></i></button> Postado em " . date_format(date_create($datahora), "H:i d/m/Y") . " por $login</p>" .
                            "<p style='margin:0px;'><a href='showMeme.php?meme=$memeId'>$countComentarios comentários</a></p>" .
                            "<div id='preview$memeId' class='w3-panel w3-white w3-round-xlarge w3-border' style='display:none;'><img src='memes/$arquivo'></div>" .
                        "</div>" .
                    "</li>";
            }
            
            echo "</ul>";
            
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </body>
</html>
