<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Resultados da Pesquisa";

// Importa controladores e classes necessários
include_once realpath(__DIR__ . '/../controllers/c-resultados.php');
include_once realpath(__DIR__ . '/../controllers/c-produtos.php');

$produtos = new Produtos();

// Captura os parâmetros de pesquisa e categoria
$query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
$categoriaId = filter_input(INPUT_GET, 'categoria_id', FILTER_SANITIZE_NUMBER_INT);

// Busca produtos com base no parâmetro recebido
if ($query) {
    $listaProdutos = $produtos->buscaProdutos($query); // Pesquisa por termo
    $tituloResultados = "Resultados para: " . htmlspecialchars($query);
} elseif ($categoriaId) {
    $listaProdutos = $produtos->buscaProdutosPorCategoria($categoriaId); // Filtro por categoria
    $tituloResultados = "Produtos da Categoria";
} else {
    $listaProdutos = [];
    $tituloResultados = "Nenhum produto encontrado.";
}

$string = "
    <div class='my-5'>
        <h1>$tituloResultados</h1>
    </div>
    
    <section class='row row-cols-1 row-cols-md-4 g-3'>
    "; ?>

<?php
    $string .= Produtos::gerarCardProdutos($listaProdutos); 
    $string .= "  
    </section>
    
    <!-- Modal para login -->
    <div class='modal fade' id='modalLogar' tabindex='-1' aria-labelledby='modalLogarLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
            <div class='modal-body'>
                Para prosseguir com a compra cadastre-se e faça login.
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Voltar</button>
                <a href='v-login.php' type='button' class='btn btn-primary'>Logar</a>
            </div>
            </div>
        </div>
    </div>
    
    <script src='../../js/ajax_compras.js'></script>";

include_once realpath(__DIR__ . '/../templates/template.php');
?>
