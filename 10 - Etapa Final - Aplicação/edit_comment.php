<?php

session_start();

require("dbConn.php");

spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});
use \Michelf\Markdown;
use \Michelf\MarkdownExtra;

$conn->begin_transaction();

// Vê se o comentário existe (se o usuário não brincou com o HTML pra dar treta), e já pega o ID do autor do comentário.
$stmt = $conn->prepare("SELECT idUsuario FROM Comentario WHERE id = ?");
$stmt->bind_param("s", $_POST["commentID"]);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($idUsuario);
$stmt->fetch();

if($stmt->num_rows == 0 || $idUsuario != $_SESSION["id"]) {
    // O comentário não existe, ou não é o autor do comentário que está tentando apagá-lo. Aborta o script.
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

date_default_timezone_set("America/Sao_Paulo");
$edit_data = date("Y-m-d H:i:s");

$stmt = $conn->prepare("UPDATE Comentario SET conteudo = ?, editado = 1, dataHoraEdit = ? WHERE id = ?");
$stmt->bind_param("sss", $_POST["commentText"], $edit_data, $_POST["commentID"]);
$stmt->execute();
$stmt->close();

$conn->commit();

$conn->close();

// Formata o texto para o AJAX.
echo Markdown::defaultTransform($_POST["commentText"]);

?>