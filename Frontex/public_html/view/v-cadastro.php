<?php
$title = "Cadastro";

$string = "
<div class='result'></div>
        <div class='container'>
            <div class='row'>
                <div class='col col-md-12 d-flex flex-column align-items-center'>
                    <h1>Cadastro</h1>
                    <form id='form_register'>
                        <div class='mb-3'>
                            <label for='nome' class='form-label'>Nome</label>
                            <input type='text' name='nome' id='register_nome' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                            <label for='email' class='form-label'>E-mail</label>
                            <input type='email' name='email' id='register_email' class='form-control' required>
                        </div>
                        <div class='mb-3'>
                            <label for='senha' class='form-label'>Senha</label>
                            <input type='password' name='senha' id='register_password' class='form-control' required>
                        </div>
                        <div class='mb-3 text-center'>
                            <div class='text-end'>
                                <small>Já tem uma conta?</small>
                            </div>
                            <button type='submit' id='btn_register' class='btn btn-primary'>
                                Cadastrar 
                            </button>
                            <a href='v-login.php' class='btn btn-secondary'>
                                Logar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>";

include_once realpath(__DIR__ . '/../templates/template-login.php');

?>