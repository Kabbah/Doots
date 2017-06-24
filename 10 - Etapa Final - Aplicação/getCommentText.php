<?php

require('dbConn.php');

$stmt = $conn->prepare("SELECT conteudo FROM Comentario WHERE id = ?");
$stmt->bind_param("s", $_POST["commentID"]);
$stmt->execute();
$stmt->bind_result($conteudo);
$stmt->fetch();
$stmt->close();

echo $conteudo;

$conn->close();

?>