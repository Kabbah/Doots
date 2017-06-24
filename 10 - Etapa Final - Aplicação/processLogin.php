<?php

require_once "dbConn.php";

session_start();

if($_POST["login"] == "" || $_POST["password"] == "") {
    setcookie("campoVazio", "Nome de usuário ou senha inválido(s).", time() + 10);
    header("Location: registerLogin.php");
    $conn->close();
    exit();
}

// Prepara o query SQL
$stmt = $conn->prepare("SELECT id, senha, avatar FROM Usuario WHERE BINARY login=?");
$stmt->bind_param("s", $_POST['login']);
$stmt->execute();
$stmt->store_result();

$stmt->bind_result($id, $senha, $avatar);
$stmt->fetch();

if($stmt->num_rows == 1 && password_verify($_POST['password'], $senha)) {
    // Seta as variáveis de sessão.
    $_SESSION["id"] = $id;
    $_SESSION['login'] = $_POST["login"];
    $_SESSION["avatar"] = $avatar;
    header("Location: /");
}
else {
    setcookie("loginInvalido", "Nome de usuário ou senha inválido(s).", time() + 10);
    header("Location: registerLogin.php");
}

$stmt->close();
$conn->close();

?>