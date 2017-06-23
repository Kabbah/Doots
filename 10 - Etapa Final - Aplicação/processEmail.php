<?php
    session_start();
    if($_POST["email"] == "" || $_POST["email-confirm"] == ""){
        setcookie("erroEmail", "Ambos os campos de Email e Confirmar email devem ser preenchidos", time()+10);
        header("location:settings.php");
        exit();
    }

    if($_POST["email"] != $_POST["email-confirm"]){
        setcookie("erroEmail", "A confirmação de email não é igual ao email inserido", time()+10);
        header("location:settings.php");
        exit();
    }
    
    require('dbConn.php');

    $query = $conn->prepare("SELECT email FROM Usuario WHERE email = ?");
    $query->bind_param("s", $_POST["email"]);
    $query->execute();
    $query->store_result();
    if ($query->num_rows > 0) {
        setcookie("erroEmail", "O email inserido já está cadastrado. Use outro email", time()+10);
        header("location:settings.php");
        exit();
    }
    $query->close();
    
    $query = $conn->prepare("UPDATE Usuario SET email = ? WHERE id = ?");
    $query->bind_param("ss", $_POST["email"], $_SESSION["id"]);
    if($query->execute()){
        setcookie("alteracaoSucesso", "Email atualizado com sucesso!", time()+10);
        header("location: settings.php");
    }
    else {
        setcookie("alteracaoErro", "Houve um erro inesperado ao tentar atualizar o email! Por favor, tente novamente em alguns minutos", time()+10);
        header("location: settings.php");
    }
    $query->close();

    $conn->close();
    exit();
?>