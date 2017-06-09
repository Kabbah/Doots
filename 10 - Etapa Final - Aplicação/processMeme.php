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

// TODO: colocar cookies nas coisas.
// TODO: fazer redirects.

// Imagem falsa
$tamanho = getimagesize($_FILES["imagem"]["tmp_name"]);
if($tamanho === false) {
    $deuboa = false;
}

// Conflito de nomes
if(file_exists($caminho)) {
    $deuboa = false;
}

// Imagem muito grande
if($_FILES["imagem"]["size"] > 10000000) {
    $deuboa = false;
}

// Imagem em formato inadequado
if($tipo != "jpg" && $tipo != "jpeg" && $tipo != "png" && $tipo != "gif" && $tipo != "svg") {
    $deuboa = false;
}

if($deuboa) {
    if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
        // Upload deu boa
        require ("dbConn.php");
        
        $stmt = $conn->prepare("INSERT INTO meme(arquivo, titulo, poster) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $arquivo, $_POST["titulo"], $_SESSION["id"]);
        $stmt->execute();
        
        // header("Location: )
    }
    else {
        // Upload não deu boa
        setcookie("erroUpload", "Ocorreu um erro no upload de seu meme. Tente novamente mais tarde.", time() + 10);
        header("Location: createMeme.php");
        exit();
    }
}
else {
    setcookie("erroUpload", "Ocorreu um erro no upload de seu meme. Tente novamente mais tarde.", time() + 10);
    header("location:creatememe.php");
    exit();
}


?>