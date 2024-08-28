<?php

include_once realpath(__DIR__ . '/../../gg/connection/connection.php');

$title = "Frontex";

include_once realpath(__DIR__ . '/../controllers/c-home.php');


$string = "
<main class = ''>
    <div class = 'container' data-aos='fade-up'>
        ";?>
    <?php
        $banners = new Banners($pdo);
        $dados = $banners->buscarBannersAtivos();
        $string .= $banners->gerarCarrouselBanners($dados);
        $string .="
        </div>
    </main>
    
    <div class = 'text-center mt-2'>
        <h1 data-aos='fade-up'>Categorias</h1>
    </div>
    
    <section data-aos='fade-left' style: 'margin: 20px'>";

    $categorias = new Categorias($pdo);
    $dados = $categorias->buscaCategorias();
    $string .= $categorias->gerarCardsCategorias($dados);
    
    $string .= "
    </section>";

    $string .= "
    <div class = 'text-center mt-2'>
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
    ";

include_once realpath(__DIR__ . '/../templates/template.php'); ?>