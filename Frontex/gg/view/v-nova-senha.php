<?php
$title = "Alterar Senha";

$string = "
<div class='row'>
                <div class='col col-md-12 d-flex flex-column align-items-center'>
                    <h1>Login</h1>
                    <form method='post' id='form_login'>
                        <div class='mb-3'>
                            <label for='username' class='form-label'>E-mail</label>
                            <input type='text' id='login_email' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                            <label for='login_password' class='form-label'>Nova Senha</label>
                            <input type='password' id='login_password' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                            <label for='confirm_password' class='form-label'>Confirmar Senha</label>
                            <input type='password' id='confirm_password' class='form-control' required>
                        </div>
                        <div class='mb-3 text-center'>
                            <a href='v-login.php'  class='btn btn-secondary'>
                                Voltar
                            </a>
                            <button type='submit' name='btn_login' id='btn_login' class='btn btn-primary'>
                                Enviar
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>";

include_once realpath(__DIR__ . '/../templates/template-login.php');

?>