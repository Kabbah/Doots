<?php

session_start();

require("dbConn.php");

$conn->begin_transaction();

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

// Outra query para previnir erros caudados por usuários sabichões.
$stmt = $conn->prepare("SELECT updoot FROM ComentarioDoot WHERE idComentario = ? AND idUsuario = ?");
$stmt->bind_param("ss", $_POST["commentID"], $_SESSION["id"]);
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
        $stmt = $conn->prepare("UPDATE Comentario SET doots = doots + 1 WHERE id = ?");
        $stmt->bind_param("s", $_POST["commentID"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM ComentarioDoot WHERE idComentario = ? AND idUsuario = ?");
        $stmt->bind_param("ss", $_POST["commentID"], $_SESSION["id"]);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->commit();

$conn->close();

?>