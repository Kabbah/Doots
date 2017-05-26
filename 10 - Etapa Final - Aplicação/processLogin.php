<?php

require_once "dbConn.php";

session_start();

$error = ""; // Mensagem de erro

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    // Fazer conexão com o banco de dados
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Verifica a conexão.
    if($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    
    // Prepara o query SQL
    $stmt = $conn->prepare("SELECT senha FROM Usuario WHERE login=? LIMIT 1");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    
    $stmt->bind_result($senha);
    $stmt->fetch();
    
    if($stmt->num_rows == 1) {
        if(password_verify($password, $senha)) {
            // Seta as variáveis de sessão.
            $_SESSION['login_user'] = $login;
        }
        else {
            $error = "Nome de usuário ou senha inválido(s).";
        }
    }
    else {
        $error = "Nome de usuário ou senha inválido(s).";
    }
    
    $stmt->close();
    
    $conn->close();
}
?>