<?php

session_start();

require("dbConn.php");

$conn->begin_transaction();

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

// Outra query para previnir erros caudados por usuários sabichões.
$stmt = $conn->prepare("SELECT updoot FROM MemeDoot WHERE idMeme = ? AND idUsuario = ?");
$stmt->bind_param("ss", $_POST["memeID"], $_SESSION["id"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($isUpdoot);
$stmt->fetch();
$rows = $stmt->num_rows;
$stmt->close();

echo "Voto existe: $rows\n";

if($rows != 0) {
    echo "É um updoot: $isUpdoot\n";
    // Se cair aqui, é porque realmente existe um voto.
    if(!$isUpdoot) {
        echo "Desfazendo downdoot";
        $stmt = $conn->prepare("UPDATE Meme SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $_POST["memeID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM MemeDoot WHERE idMeme = ? AND idUsuario = ?");
        $stmt->bind_param("ss", $_POST["memeID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE Usuario SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $poster);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->commit();

$conn->close();

?>