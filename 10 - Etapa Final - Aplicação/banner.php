<header class="w3-container w3-purple banner">
    <h1 class="w3-left"><a class="logo" href="/">Doots</a></h1> <!-- Vai virar uma imagem -->
    <?php
        if(isset($_SESSION['login'])) {
            echo "<div class='w3-right' style='margin-top:5px;'>" .
                    "<img class='avatar welcome w3-round' src='avatares/{$_SESSION['avatar']}' alt='Avatar' style='margin-right:5px;'>" .
                    "<h2 class='welcome' style='margin-right:20px;'>{$_SESSION['login']}</h2>" .
                    "<a href='settings.php' style='vertical-align:middle;margin-right:10px;'><i class='fa fa-cog' style='font-size:36px;color:black;vertical-align:middle;'></i></a>" .
                    "<a href='logout.php' style='vertical-align:middle;'><i class='fa fa-sign-out' style='font-size:36px;color:black;vertical-align:middle;'></i></a>" .
                "</div>";
        }
        else {
            echo "<div class='w3-right'>" .
                    "<h2 class='w3-btn'><a href='registerLogin.php'>Logar ou Registrar</a></h2>" .
                "</div>";
        }
    ?>
</header>