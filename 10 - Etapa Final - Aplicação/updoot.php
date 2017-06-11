<?php

session_start();

require("dbConn.php");

$conn->begin_transaction();

$jaDeuUpdoot = false;
$jaDeuDowndoot = false;

// Vê se o meme existe (se o usuário não brincou com o HTML pra dar treta), e já pega o ID do autor do meme.
$stmt = $conn->prepare("SELECT poster FROM Meme WHERE id = ?");
$stmt->bind_param("s", $_POST["memeID"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($poster);
$stmt->fetch();

echo "Meme existe: $stmt->num_rows\n";

if($stmt->num_rows == 0) {
    // O meme não existe, aborta o script.
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

echo "Poster: $poster\n";

$stmt = $conn->prepare("SELECT updoot FROM MemeDoot WHERE idMeme = ? AND idUsuario = ?");
$stmt->bind_param("ss", $_POST["memeID"], $_SESSION["id"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($isUpdoot);
$stmt->fetch();

echo "Voto existe: $stmt->num_rows\n";

if($stmt->num_rows != 0) {
    echo "É um updoot: $isUpdoot\n";
    // Se cair aqui, é porque o usuário já votou nesse meme.
    if($isUpdoot) {
        // O voto foi um updoot. Portanto, o script não deve fazer nada.
        $jaDeuUpdoot = true;
    }
    else {
        // O voto foi um downdoot. Portanto, o script deve trocar esse downdoot por um updoot.
        $jaDeuDowndoot = true;
    }
}
$stmt->close();

if(!$jaDeuUpdoot) {
    if(!$jaDeuDowndoot) { // Updoot novo.
        echo "Novo updoot";
        $stmt = $conn->prepare("UPDATE Meme SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $_POST["memeID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO MemeDoot(idMeme, idUsuario, updoot) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $_POST["memeID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE Usuario SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $poster);
        $stmt->execute();
        $stmt->close();
    }
    else { // Troca de um downdoot por um updoot.
        echo "Substituindo downdoot";
        $stmt = $conn->prepare("UPDATE Meme SET doots = doots + 2 WHERE id = ?");
        $stmt->bind_param("s", $_POST["memeID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE MemeDoot SET updoot = 1 WHERE idMeme = ? AND idUsuario = ?");
        $stmt->bind_param("ss", $_POST["memeID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE Usuario SET doots = doots + 2 WHERE id = ?");
        $stmt->bind_param("s", $poster);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->commit();

$conn->close();

?>