<?php
$title = "Login";

// Interpolando variáveis PHP corretamente na string
$string = "
<div class='result'></div>
<div class='container'>
    <div class='row'>
        <div class='col col-md-12 d-flex flex-column align-items-center'>
            <h1>Login</h1>
            <form method='post' id='form_login'>
                <div class='mb-3'>
                    <label for='email' class='form-label'>E-mail</label>
                    <input type='text' name='email' id='login_email' class='form-control' required value='" . (isset($_COOKIE['LE']) ? htmlspecialchars($_COOKIE['LE'], ENT_QUOTES, 'UTF-8') : '') . "'>
                </div>
                <div class='mb-3'>
                    <label for='senha' class='form-label'>Senha</label>
                    <input type='password' name='senha' id='login_password' class='form-control' required value='" . (isset($_COOKIE['LP']) ? htmlspecialchars($_COOKIE['LP'], ENT_QUOTES, 'UTF-8') : '') . "'>
                </div>
                <div class='mb-3'>
                    <input type='checkbox' name='login_remember' id='remember' " . (isset($_COOKIE['LP']) && $_COOKIE['LP'] != '' ? 'checked' : '') . "> Lembrar senha
                    <a href='v-recuperar-senha.php' name='nova_senha' id='nova_senha' style='margin-left: 20px;'> Esqueceu a senha?</a>
                </div>
                <div class='mb-3 text-center'>
                    <a href='v-cadastro.php' name='btn_cadastrar' id='btn_cadastrar' class='btn btn-secondary'>
                        Cadastre-se
                    </a>
                    <button type='submit' name='btn_login' id='btn_login' class='btn btn-primary'>
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
";

include_once realpath(__DIR__ . '/../templates/template-login.php');
?>
