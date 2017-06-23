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

if($stmt->num_rows == 0 || $idUsuario != $_SESSION["id"]) {
    // O comentário não existe, ou não é o autor do comentário que está tentando apagá-lo. Aborta o script.
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE Comentario SET deletado = 1 WHERE id = ?");
$stmt->bind_param("s", $_POST["commentID"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($isUpdoot);
$stmt->fetch();

$conn->commit();

$conn->close();

?>