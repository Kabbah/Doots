<?php
    session_start();
    if(!isset($_FILES['avatar'])) {
        setcookie("avatarVazio", "Não foi feito o upload para o servidor. Provavelmente o servidor rejeitou o arquivo por ser muito grande.", time() + 10);
        header("location: settings.php");
        exit();
    }
    if (empty($_FILES['avatar']['name'])) {
        setcookie("avatarVazio", "Você deve fazer upload de uma imagem. E já não falei para parar de alterar o HTML?", time() + 10);
        header("location: settings.php");
        exit();
    }
    
    $enviado = basename($_FILES["avatar"]["name"]);
    $tipo = pathinfo($enviado, PATHINFO_EXTENSION);

    if($tipo != "jpg" && $tipo != "jpeg" && $tipo != "png" && $tipo != "gif" && $tipo != "svg") {
        setcookie("erroUpload", "O formato é inválido. Favor enviar apenas arquivos do tipo JPG, JPEG, PNG, GIF ou SVG.", time() + 10);
        header("location: settings.php");
        exit();
    }
        
    $tamanho = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($tamanho === false) {
        setcookie("erroUpload", "Não foi possível computar o tamanho da imagem.", time() + 10);
        header("location: settings.php");
        exit();
    }

    if($_FILES["avatar"]["size"] > 10000000) {
        setcookie("erroUpload", "A imagem é muito grande. Favor enviar um arquivo de até 9,5 MB.", time() + 10);
        header("location: settings.php");
        exit();
    }

    $pasta = "avatares/";
    $arquivo = md5(basename($_FILES["avatar"]["name"]) . $_SESSION["login"] . time()) . ".$tipo";
    $caminho = $pasta . $arquivo;

    if(file_exists($caminho)) {
        setcookie("erroUpload", "Ocorreu um erro no upload de seu meme. Tente novamente mais tarde.", time() + 10);
        header("location: settings.php");
        exit();
    }
    
    require('dbConn.php');
    
    $query = $conn->prepare("SELECT avatar FROM Usuario WHERE id = ?");
    $query->bind_param("s", $_SESSION["id"]);
    $query->execute();
    $query->bind_result($avatarAntigo);
    $query->fetch();
    $query->close();
    
    if(move_uploaded_file($_FILES["avatar"]["tmp_name"], $caminho)) {
        $query = $conn->prepare("UPDATE Usuario SET avatar = ? WHERE id = ?");
        $query->bind_param("ss", $arquivo, $_SESSION["id"]);
        if($query->execute()) {
            $query->close();
            setcookie("alteracaoSucesso", "Senha atualizada com sucesso!", time()+10);
            if($avatarAntigo != "avatar.png") {
                unlink($pasta . $avatarAntigo);
                $_SESSION["avatar"] = $arquivo;
            }
            header("location: settings.php");
        }
        else {
            setcookie("alteracaoErro", "Houve um erro inesperado ao tentar atualizar o avatar! Por favor, tente novamente em alguns minutos", time()+10);
            header("location: settings.php");
        }
      
    }
    else {
        setcookie("erroUpload", "Ocorreu um erro no upload de seu avatar. Tente novamente mais tarde.", time() + 10);
        header("Location: settings.php");
        exit();
    }
    
    $conn->close();
    exit();
?>