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
        <h1 data-aos='fade-up'>Produtos</h1>
    </div>

    <section class='row row-cols-1 row-cols-md-4 g-3'>
    ";?>
<?php
    $produtos = new Produtos();
    $dados = $produtos->buscarProdutosAtivos();
    $string .= Produtos::gerarCardsProdutos($dados); 
    $string .="  
    </section>
    ";


include_once realpath(__DIR__ . '/../templates/template.php'); ?>