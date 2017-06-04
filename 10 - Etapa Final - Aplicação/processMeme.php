<?php

session_start();

if($_POST["titulo"] == "") {
    setcookie("tituloVazio", "Seu meme precisa de um título.", time() + 10);
    header("Location: registerLogin.php");
    exit();
}

$enviado = basename($_FILES["imagem"]["name"]);
$tipo = pathinfo($enviado, PATHINFO_EXTENSION);

$pasta = "memes/";
$arquivo = md5(basename($_FILES["imagem"]["name"]) . $_SESSION["login"] . time()) . ".$tipo";
$caminho = $pasta . $arquivo;

$deuboa = 1;

// TODO: colocar cookies nas coisas.
// TODO: fazer redirects.

// Imagem falsa
$tamanho = getimagesize($_FILES["imagem"]["tmp_name"]);
if($tamanho === false) {
    $deuboa = 0;
}

// Conflito de nomes
if(file_exists($caminho)) {
    $deuboa = 0;
}

// Imagem muito grande
if($_FILES["imagem"]["size"] > 500000) {
    $deuboa = 0;
}

// Imagem em formato inadequado
if($tipo != "jpg" && $tipo != "jpeg" && $tipo != "png" && $tipo != "gif" && $tipo != "svg") {
    $deuboa = 0;
}

if($deuboa) {
    if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
        // Upload deu boa
        require "dbConn.php";
        
        $stmt = $conn->prepare("INSERT INTO Meme(arquivo, titulo, poster) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $arquivo, $_POST["titulo"], $_SESSION["id"]);
        $stmt->execute();
    }
    else {
        // Upload não deu boa
    }
}

?>