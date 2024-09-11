<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Frontex";

include_once realpath(__DIR__ . '/../controllers/c-home.php');


$string = "
<main>
    <div class = 'container' data-aos='fade-up'>
        ";?>
    <?php
        $banners = new Banners($pdo);
        $dados = $banners->buscarBannersAtivos();
        $string .= $banners->gerarCarrouselBanners($dados);
        $string .="
        </div>
    </main>
    
    <div class = 'text-center m-5'>
        <h1 data-aos='fade-up'>Categorias</h1>
    </div>
    
    <section data-aos='fade-left' >";

    $categorias = new Categorias($pdo);
    $dados = $categorias->buscaCategorias();
    $string .= $categorias->gerarCardsCategorias($dados);
    
    $string .= "
    </section>";

    $string .= "
    <div class = 'text-center m-5'>
        <h1 data-aos='fade-up'>Produtos</h1>
    </div>

    <section class='row row-cols-1 row-cols-md-4 g-3'>
    ";?>
<?php
    $produtos = new Produtos();
    $dados = $produtos->buscaProdutos();
    $string .= Produtos::gerarCardProdutos($dados); 
    $string .="  
    </section>
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

include_once realpath(__DIR__ . '/../templates/template.php'); ?>