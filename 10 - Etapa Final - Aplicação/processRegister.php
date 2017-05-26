<?php    
    // Primeiro checa se o usuário não tentou inserir merda

    $erro = false;
    if ($_POST["login"] == "" || $_POST["email"] == "" || $_POST["confirm-email"] == "" || $_POST["password"] == "" || $_POST["confirm-password"] == "") {
        setcookie("campoVazio", "Todos os campos devem ser preenchidos", time()+10);
        $erro = true;
    }

    if (!isset($_POST["tos"])) {
        setcookie("tos", "Você deve aceitar os Termos de Serviço para se registrar", time()+10);
        $erro = true;
    }

    if (strlen($_POST["login"]) > 32) {
        setcookie("loginLongo", "O nome de usuário inserido é muito longo", time()+10);
        $erro = true;
    }

    if ($_POST["email"] != $_POST["confirm-email"]) {
        setcookie("confirmacaoEmail", "Os emails digitados não são iguais");
        $erro = true;
    }

    if ($_POST["password"] != $_POST["confirm-password"]) {
        setcookie("confirmacaoPassword", "As senhas digitadas não são iguais");
        $erro = true;
    }
    
    if($erro == true){
        header("location: registerLogin.php");
        exit();
    }

    require('dbConn.php');

    $query = $conn->prepare("SELECT login FROM Usuario WHERE login=?");
    $query->bind_param("s", $_POST["login"]);
    $query->execute();

    if ($query->num_rows > 0) {
        setcookie("usuarioExiste", "O nome de usuário inserido já existe. Escolha outro nome");
        $erro = true;
    }

    $query = $conn->prepare("SELECT email FROM Usuario WHERE email=?");
    $query->bind_param("s", $_POST["email"]);
    $query->execute();

    if ($query->num_rows > 0) {
        setcookie("emailExiste", "O email inserido já está cadastrado. Use outro email");
        $erro = true;
    }

    if($erro == true){
        header("location: registerLogin.php");
        $conn->close();
        exit();
    }

    $senha = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = $conn->prepare("INSERT INTO Usuario(login, senha, email) VALUES (?, ?, ?)")
    $query->bind_param("sss", $_POST["login"], $senha, $_POST["email"]);
    if ($query->execute() == true){
        setcookie("registroSucesso", "Conta criada com sucesso!", time()+10);
        header("location: registerLogin.php");
    }
    else {
        echo $conn->error;
    }
    $conn->close();
    exit();
?>