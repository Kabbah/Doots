<?php
    session_start();
    if($_POST["password"] == "" || $_POST["password-confirm"] == ""){
        setcookie("erroPassword", "Ambos os campos de Senha e Confirmar senha devem ser preenchidos", time()+10);
        header("location:settings.php");
        exit();
    }

    if($_POST["password"] != $_POST["password-confirm"]){
        setcookie("erroPassword", "A confirmação de senha não é igual a senha inserido", time()+10);
        header("location:settings.php");
        exit();
    }
    
    require('dbConn.php');

    $query = $conn->prepare("SELECT senha FROM Usuario WHERE id = ?");
    $query->bind_param("s", $_SESSION["id"]);
    $query->execute();
    $query->store_result();
    $query->bind_result($senhaAtual);
    $query->fetch();

    if($query->num_rows == 1 && password_verify($_POST["password-now"], $senhaAtual)) {
        $query->close();
        $senha = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $query = $conn->prepare("UPDATE Usuario SET senha = ? WHERE id = ?");
        $query->bind_param("ss", $senha, $_SESSION["id"]);
        
        if($query->execute()){
            setcookie("alteracaoSucesso", "Senha atualizada com sucesso!", time()+10);
            header("location: settings.php");
        }
        else {
            setcookie("alteracaoErro", "Houve um erro inesperado ao tentar atualizar a senha! Por favor, tente novamente em alguns minutos", time()+10);
            header("location: settings.php");
        }
        
        $query->close();
    }
    else {
        setcookie("erroPassword", "A senha atual inserida é inválida", time()+10);
        header("location:settings.php");
    }
    $conn->close();
    exit();
?>