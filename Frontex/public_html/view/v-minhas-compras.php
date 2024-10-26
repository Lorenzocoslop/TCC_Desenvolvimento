<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Carrinho";

include_once realpath(__DIR__ . '/../controllers/c-home.php');
include_once realpath(__DIR__ . '/../controllers/c-minhas-compras.php');

$compras = new MinhasCompras();
$dados = $compras->buscaProdutos();
$string = "
<main class='bg-tertiary'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <div class='bg-light m-5 border rounded'>
                    <h2 class='text-center border-bottom border-dark'>Minhas Compras</h2>
                    
";
$string .= MinhasCompras::listarPedidos($dados);
$string .= "
                </div>
            </div>
        </div>
    </div>
</main>

";
$string.= gerarModaisTabela($obj);
    if(isset($_SESSION['logged']) && $_SESSION['logged'] === 1){
        $string .="
        <script>atualizarNotificacoes();</script>";
        }


include_once realpath(__DIR__ . '/../templates/template.php'); ?>