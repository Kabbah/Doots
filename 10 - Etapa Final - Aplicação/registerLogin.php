<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Login ou Registro</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="css/main.css">
        
        <script>
            function changeTab(evt, cityName) {
                var i, tabs;
                var x = document.getElementsByClassName("log-reg");
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                
                tabs = document.getElementsByClassName("tab");
                for (i = 0; i < tabs.length; i++) {
                    tabs[i].className = tabs[i].className.replace(" w3-black", "");
                }
                
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " w3-black";
            }
        </script>
    </head>
    
    <body>
        <?php
            require('banner.php');
        ?>
        <div class="login-wrapper">
            <?php
                if (isset($_COOKIE["campoVazio"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['campoVazio'] . '</b></p>';
                }
                if (isset($_COOKIE["loginInvalido"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['loginInvalido'] . '</b></p></div>';
                }
                if (isset($_COOKIE["tos"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['tos'] . '</b></p></div>';
                }
                if (isset($_COOKIE["loginLongo"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['loginLongo'] . '</b></p></div>';
                }
                if (isset($_COOKIE["confirmacaoEmail"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['confirmacaoEmail'] . '</b></p></div>';
                }
                if (isset($_COOKIE["confirmacaoPassword"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['confirmacaoPassword'] . '</b></p></div>';
                }
                if (isset($_COOKIE["usuarioExiste"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['usuarioExiste'] . '</b></p></div>';
                }
                if (isset($_COOKIE["emailExiste"])){
                    echo '<div class="error"><p class="w3-panel w3-red"><b>' . $_COOKIE['emailExiste'] . '</b></p></div>';
                }
                if (isset($_COOKIE["registroSucesso"])){
                    echo '<div class="error"><p class="w3-panel w3-green"><b>' . $_COOKIE['registroSucesso'] . '</b></p></div>';
                }
            
            ?>
            <div class="form-pad w3-dark-gray">
                <button class="w3-button w3-half tab w3-black" onclick="changeTab(event, 'login')">Login</button>
                <button class="w3-button w3-half tab" onclick="changeTab(event, 'register')">Registrar</button>
            </div>
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" id="login" method="post" action="processLogin.php">
                <br/>
                <label class="w3-text-purple"><b>Usuário</b></label>
                <input class="w3-input w3-border" type="text" name="login" required>
                

                <label class="w3-text-purple"><b>Senha</b></label>
                <input class="w3-input w3-border" type="password" name="password" required>
                
                <br/>
                <input class="w3-button w3-purple form-submit-button" type="submit" value="Entrar">
            </form>
            
            <form class="w3-container w3-border-bottom w3-border-left w3-border-right w3-animate-opacity log-reg" id="register" style="display:none" method="post" action="processRegister.php">
                <br/>
                <label class="w3-text-purple"><b>Usuário</b></label>
                <input class="w3-input w3-border" type="text" name="login" placeholder="Usuario" required>
                
                <br/>
                <label class="w3-text-purple"><b>Email</b></label>
                <input class="w3-input w3-border" type="email" name="email" placeholder="email@exemplo.com" required>
                
                <br/>
                <label class="w3-text-purple"><b>Confirmar Email</b></label>
                <input class="w3-input w3-border" type="email" name="confirm-email" placeholder="email@exemplo.com" required>
                
                <br/>
                <label class="w3-text-purple"><b>Senha</b></label>
                <input class="w3-input w3-border" type="password" name="password" placeholder="&#9679&#9679&#9679&#9679&#9679" required>
                
                <br/>
                <label class="w3-text-purple"><b>Corfirmar Senha</b></label>
                <input class="w3-input w3-border" type="password" name="confirm-password" placeholder="&#9679&#9679&#9679&#9679&#9679" required>
                
                <br/>
                <input class="w3-check" type="checkbox" name="tos" required>
                <label>Eu li e concordo com os <a href="#">Termos de Serviço</a></label>
                
                <br/>
                <input class="w3-button w3-purple form-submit-button" type="submit" value="Registrar">
            </form>
        </div>
        <br/>
    </body>
</html>
