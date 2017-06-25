<?php
session_start();
if(!isset($_GET["search"])) {
    header("location: /");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Memes - Doots</title>
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
                <?php
                    if (isset($_SESSION["login"])) {
                        echo 'var memeID = btn.value;

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
                        echo 'var memeID = btn.value;

                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.open("POST", "downdoot.php", true);
                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp.send("memeID=" + memeID);

                        // Depois de fazer um downdoot, tem que mudar o botão.
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
                    } ';
            }
            ?>
        </script>
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div>
            <h2 style="margin-left:10px;">Pesquisa</h2>
        </div>
        <div>
            <?php
            require ("dbConn.php");
            
            $offset = 0;
            $proximaPagina = 2;
            if(isset($_GET["pagina"])) {
                $offset = 10 * ($_GET["pagina"] - 1);
                $proximaPagina = $_GET["pagina"] + 1;
            }
            
            $search = "%" . $_GET["search"] . "%";
            
            $stmt = $conn->prepare("SELECT Meme.id, Meme.titulo, Meme.arquivo, Meme.doots, Meme.dataHora, Usuario.login, Usuario.doots, count(Comentario.id), ((sign(Meme.doots) * log(10, greatest(abs(Meme.doots),1))) + (unix_timestamp(Meme.dataHora) - 1134028003)/45000) AS popularity, MemeDoot.updoot FROM Meme INNER JOIN Usuario ON (Meme.poster = Usuario.id) LEFT JOIN MemeDoot ON (Meme.id = MemeDoot.idMeme AND MemeDoot.idUsuario = ?) LEFT JOIN Comentario ON Meme.id = Comentario.idMeme WHERE Meme.deletado = '0' AND (lower(Meme.titulo) LIKE lower(?) OR lower(Usuario.login) LIKE lower(?)) GROUP BY Meme.id ORDER BY popularity DESC LIMIT 10 OFFSET ?");
            $stmt->bind_param("sssi", $_SESSION["id"], $search, $search, $offset);
            $stmt->execute();

            $stmt->store_result();

            echo "<ul class='w3-ul'>";
            
            if($stmt->num_rows == 0) {
                echo "<li class='w3-padding-16'>Nenhum resultado encontrado.</li>";
            }

            $stmt->bind_result($memeId, $titulo, $arquivo, $doots, $datahora, $login, $userdoots, $countComentarios, $popularidade, $updoot);
            while($stmt->fetch()) {
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
                            "<p style='margin:0px;'><button class='w3-button' value='$memeId' onclick='openPreview(this)'><i class='fa fa-image'></i></button> Postado em " . date_format(date_create($datahora), "H:i d/m/Y") . " por <a href='user.php?login=$login'>$login</a> ($userdoots)</p>" .
                            "<p style='margin:0px;'><a href='showMeme.php?meme=$memeId'>$countComentarios comentários</a></p>" .
                            "<div id='preview$memeId' class='w3-panel w3-white w3-round-xlarge w3-border' style='display:none;'><img src='memes/$arquivo'></div>" .
                        "</div>" .
                    "</li>";
            }

            echo "</ul>";

            echo "<div class ='w3-center'>" . 
                    "<div class='w3-bar'>";
            if($proximaPagina >= 3) {
                echo "<a href='search.php?search={$_GET["search"]}&pagina=" . ($proximaPagina - 2) . " 'class='w3-button w3-border w3-round'>&#10094; Anterior</a>";
            }
            echo "<span>Página " . ($proximaPagina - 1) . "</span>";
            if($stmt->num_rows == 10) {
                echo "<a href='search.php?search={$_GET["search"]}&pagina=$proximaPagina' class='w3-button w3-right w3-border w3-round'>Próxima &#10095;</a>";
            }
            echo "</div></div>";

            $stmt->close();
            
            $conn->close();
            ?>
        </div>
    </body>
</html>
