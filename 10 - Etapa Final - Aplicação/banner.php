<header class="w3-container w3-purple banner">
    <h1 class="w3-left"><a class="logo" href="logado.php">Doots</a></h1> <!-- Vai virar uma imagem -->
    <?php
        if(isset($_SESSION['login'])) {
            echo '<div class="w3-right">
                    <h2 class="welcome">Bem vindo '.$_SESSION['login'].'</h2>
                    <img class="avatar welcome" src="avatares/'.$_SESSION['avatar'].'" class="w3-round" alt="Avatar">
                    <a href="settings.php"><img class="welcome" id="cog" src="imagens/cog.png" alt="cog"></a>
                </div>';
        }
        else {
            echo '<div class="w3-right">
                    <h2 class="w3-btn"><a href="registerLogin.php">Logar ou Registrar</a></h2>
                  </div>';
        }
    ?>
</header>