<?php

session_start();

if(!isset($_SESSION['login'])) {
    exit();
}

if(!isset($_POST["meme"]) || $_POST["meme"] == "") {
    exit();
}

if(!isset($_POST["reacao"]) || $_POST["reacao"] == "") {
    exit();
}

require_once "dbConn.php";

$stmt = $conn->prepare("SELECT idReacao FROM MemeReacao WHERE idMeme = ? AND idUsuario = ? AND idReacao = ?");
$stmt->bind_param("sss", $_POST["meme"], $_SESSION["id"], $_POST["reacao"]);
$stmt->execute();
$stmt->bind_result($idReacao);

if(!$stmt->fetch()) {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO MemeReacao (idMeme, idUsuario, idReacao) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST["meme"], $_SESSION["id"], $_POST["reacao"]);
    $stmt->execute();
    $stmt->close();
}
else {
    $stmt->close();
}

$conn->close();

?>