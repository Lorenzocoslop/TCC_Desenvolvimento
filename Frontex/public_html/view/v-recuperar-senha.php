<?php
$title = "Recuperar Senha";

$string = "
<div class='result'></div>
<div class='row'>
        <div class='col col-md-12 d-flex flex-column align-items-center'>
            <h1>Login</h1>
            <form method='post' id='form_password'>
                <div class='mb-3'>
                    <label for='username' class='form-label'>E-mail</label>
                    <input type='text' id='email' name='email' class='form-control' required>
                </div>
                <div class='mb-3 text-center'>
                    <a href='v-login.php'  class='btn btn-secondary'>
                        Voltar
                    </a>
                    <button type='submit' name='btn_password' id='btn_password' class='btn btn-primary'>
                        Enviar
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>
";

include_once realpath(__DIR__ . '/../templates/template-login.php');

?>