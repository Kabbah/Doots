<?php

session_start();

if(!isset($_SESSION['login'])) {
    exit();
}

require("dbConn.php");

$conn->begin_transaction();

$jaDeuUpdoot = false;
$jaDeuDowndoot = false;

// Vê se o comentário existe (se o usuário não brincou com o HTML pra dar treta), e já pega o ID do autor do comentário.
$stmt = $conn->prepare("SELECT idUsuario FROM Comentario WHERE id = ?");
$stmt->bind_param("s", $_POST["commentID"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($idUsuario);
$stmt->fetch();

echo "Comentário existe: $stmt->num_rows\n";

if($stmt->num_rows == 0) {
    // O comentário não existe, aborta o script.
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

echo "Autor do comentário: $idUsuario\n";

$stmt = $conn->prepare("SELECT updoot FROM ComentarioDoot WHERE idComentario = ? AND idUsuario = ?");
$stmt->bind_param("ss", $_POST["commentID"], $_SESSION["id"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($isUpdoot);
$stmt->fetch();

echo "Voto existe: $stmt->num_rows\n";

if($stmt->num_rows != 0) {
    echo "É um updoot: $isUpdoot\n";
    // Se cair aqui, é porque o usuário já votou nesse comentário.
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
        $stmt = $conn->prepare("UPDATE Comentario SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $_POST["commentID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO ComentarioDoot(idComentario, idUsuario, updoot) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $_POST["commentID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    }
    else { // Troca de um downdoot por um updoot.
        echo "Substituindo downdoot";
        $stmt = $conn->prepare("UPDATE Comentario SET doots = doots + 2 WHERE id = ?");
        $stmt->bind_param("s", $_POST["commentID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE ComentarioDoot SET updoot = 1 WHERE idComentario = ? AND idUsuario = ?");
        $stmt->bind_param("ss", $_POST["commentID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->commit();

$conn->close();

?>