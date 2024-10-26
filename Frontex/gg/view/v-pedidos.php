<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "GG Pedidos";

include_once realpath(__DIR__ . '/../controllers/c-pedidos.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_empresa = $_SESSION['ID_empresa'];
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

$resultado = obterPedidosPaginados($pdo, $id_empresa, $paginaAtual, 10);

$obj = array_map(function($view) {
    return (object) $view; 
}, $resultado['pedidos']);

$string = "
    <div class='col-12 text-center'>
        <h3>
            Pedidos
        </h3>
    </div>
    <table class='table table-striped'>
";

$string .= gerarThead($dados);
$string .= gerarTbody($obj);

$string .= "</table>";

$string .= gerarModaisTabela($obj);

$string .= '<nav aria-label="Page navigation">';
$string .= '<ul class="pagination">';

if ($paginaAtual > 1) {
    $string .= "<li class='page-item'><a class='page-link' href='?pagina=" . ($paginaAtual - 1) . "' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
}

for ($i = 1; $i <= $resultado['totalPaginas']; $i++) {
    $activeClass = ($i == $paginaAtual) ? 'active' : '';
    $string .= "<li class='page-item $activeClass'><a class='page-link' href='?pagina=$i'>$i</a></li>";
}

if ($paginaAtual < $resultado['totalPaginas']) {
    $string .= "<li class='page-item'><a class='page-link' href='?pagina=" . ($paginaAtual + 1) . "' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
}

$string .= '</ul>';
$string .= '</nav>';

$string .= "<script src='../../js/ajax_alterar_status.js'></script>";

include_once realpath(__DIR__ . '/../templates/template.php'); 
?>
