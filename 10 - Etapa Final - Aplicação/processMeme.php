<?php

session_start();
$deuboa = true;

if($_POST['titulo'] == "") {
    setcookie("tituloVazio", "Seu meme precisa de um título", time() + 10);
    $deuboa = false;
}

if(!isset($_FILES['imagem'])) {
    setcookie("memeVazio", "Não foi feito o upload para o servidor. Provavelmente o servidor rejeitou o arquivo por ser muito grande.", time() + 10);
    $deuboa = false;
}

if (empty($_FILES['imagem']['name'])) {
    setcookie("memeVazio", "Você deve fazer upload de uma imagem. E pare de mexer no HTML!", time() + 10);
    $deuboa = false;
}

if(!$deuboa) {
    header("location:creatememe.php");
    exit();
}

$enviado = basename($_FILES["imagem"]["name"]);
$tipo = pathinfo($enviado, PATHINFO_EXTENSION);

$pasta = "memes/";
$arquivo = md5(basename($_FILES["imagem"]["name"]) . $_SESSION["login"] . time()) . ".$tipo";
$caminho = $pasta . $arquivo;

$deuboa = true;

// Imagem falsa
$tamanho = getimagesize($_FILES["imagem"]["tmp_name"]);
if($tamanho === false) {
    $deuboa = false;
    setcookie("erroUpload", "Não foi possível computar o tamanho da imagem.", time() + 10);
}

// Conflito de nomes
if(file_exists($caminho)) {
    $deuboa = false;
    setcookie("erroUpload", "Ocorreu um erro no upload de seu meme. Tente novamente mais tarde.", time() + 10);
}

// Imagem muito grande
if($_FILES["imagem"]["size"] > 10000000) {
    $deuboa = false;
    setcookie("erroUpload", "A imagem é muito grande. Favor enviar um arquivo de até 9,5 MB.", time() + 10);
}

// Imagem em formato inadequado
if($tipo != "jpg" && $tipo != "jpeg" && $tipo != "png" && $tipo != "gif" && $tipo != "svg") {
    $deuboa = false;
    setcookie("erroUpload", "O formato é inválido. Favor enviar apenas arquivos do tipo JPG, JPEG, PNG, GIF ou SVG.", time() + 10);
}

if($deuboa) {
    if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
        // Upload deu boa
        require ("dbConn.php");
        
        $stmt = $conn->prepare("INSERT INTO meme(arquivo, titulo, poster) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $arquivo, $_POST["titulo"], $_SESSION["id"]);
        $stmt->execute();
        
        $stmt = $conn->prepare("SELECT id FROM Meme WHERE arquivo = ?");
        $stmt->bind_param("s", $arquivo);
        $stmt->execute();
        $stmt->bind_result($idMeme);
        $stmt->fetch();
        
        header("Location: showMeme.php?meme=$idMeme");
        exit;
    }
    else {
        // Upload não deu boa
        setcookie("erroUpload", "Ocorreu um erro no upload de seu meme. Tente novamente mais tarde.", time() + 10);
        header("Location: createMeme.php");
        exit();
    }
}
else {
    header("location:creatememe.php");
    exit();
}


?>