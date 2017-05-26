<?php

require_once "dbConn.php";

session_start();

if($_POST["login"] == "" || $_POST["password"] == "") {
    setcookie("campoVazio", "Nome de usuário ou senha inválido(s).", time() + 10);
    header("Location: registerLogin.php");
}

// Prepara o query SQL
$stmt = $conn->prepare("SELECT senha FROM Usuario WHERE login=?");
$stmt->bind_param("s", $_POST['login']);
$stmt->execute();

$stmt->bind_result($senha);
$stmt->fetch();

if($stmt->num_rows == 1 && password_verify($_POST['password'], $senha)) {
    // Seta as variáveis de sessão.
    $_SESSION['login_user'] = $login;
    header("Location: logado.php");
    exit;
}
else {
    setcookie("loginInvalido", "Nome de usuário ou senha inválido(s).", time() + 10);
}

$stmt->close();
$conn->close();

?>