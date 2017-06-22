<?php

session_start();

if(!isset($_POST["meme"]) || $_POST["meme"] == "") {
    header("Location: /");
    exit();
}

if(!isset($_POST["comentario"]) || $_POST["comentario"] == "") {
    header("Location: showMeme.php?meme={$_POST["meme"]}");
    exit();
}

require_once "dbConn.php";

// Prepara o query SQL
$stmt = $conn->prepare("INSERT INTO Comentario (conteudo, conteudoOri, idUsuario, idMeme) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $_POST["comentario"], $_POST["comentario"], $_SESSION["id"], $_POST["meme"]);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: showMeme.php?meme={$_POST["meme"]}");

?>