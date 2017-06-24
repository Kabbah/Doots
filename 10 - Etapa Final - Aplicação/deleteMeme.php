<?php

session_start();

require("dbConn.php");

$conn->begin_transaction();

// Vê se o meme existe (se o usuário não brincou com o HTML pra dar treta), e já pega o ID do autor do meme.
$stmt = $conn->prepare("SELECT poster FROM Meme WHERE id = ?");
$stmt->bind_param("s", $_POST["memeID"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($idUsuario);
$stmt->fetch();

if($stmt->num_rows == 0 || $idUsuario != $_SESSION["id"]) {
    // O meme não existe, ou não é o autor do meme que está tentando apagá-lo. Aborta o script.
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE Meme SET deletado = 1 WHERE id = ?");
$stmt->bind_param("s", $_POST["memeID"]);
$stmt->execute();

$stmt->close();

$conn->commit();

$conn->close();

header("Location: showMeme.php?meme={$_POST["memeID"]}");

?>