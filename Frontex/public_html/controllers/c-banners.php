<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');




class Banners {
    private $pdo;
    private $tabela;
    private $empresa;

    public function __construct($pdo, $tabela = 'banners') {
        $this->pdo = $pdo;
        $this->tabela = $tabela;
        $this->empresa = new Empresa();
    }

    public function buscarBannersAtivos() {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->tabela . ' WHERE ativo <> 0 AND ID_empresa = :id_empresa');
        $stmt->execute([':id_empresa' => $this->empresa->getID_empresa()]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

    public function gerarCarrouselBanners($dados) {
        if (empty($dados)) {
            return "<p>Nenhuma imagem disponível para o carrossel.</p>";
        }

        $carouselIndicators = '';
        $carouselItems = '';

        foreach ($dados as $index => $view) {
            $imagemPath = $view->img;
            $isActive = ($index === 0) ? 'active' : ''; 

            $carouselIndicators .= "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='$index' class='$isActive' aria-current='true' aria-label='Slide ".($index + 1)."'></button>";

            $carouselItems .= "
            <div class='carousel-item $isActive' data-bs-interval='20000'>
                <img src='$imagemPath' class='d-block img-fluid' alt='Imagem do Slide' style = 'width: 1200px; height: 400px'>
            </div>";
        }

        return "
            <div id='carouselExampleCaptions' class='carousel slide' style='max-width: 1200px; max-height: 600px; margin: 0 auto;' data-bs-ride='carousel'>
            <div class='carousel-indicators'>
                $carouselIndicators
            </div>
            <div class='carousel-inner'>
                $carouselItems
            </div>
            <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide='prev'>
                <span class='visually-hidden'>Previous</span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide='next'>
                <span class='visually-hidden'>Next</span>
            </button>
            </div>";
    }
}

?>
