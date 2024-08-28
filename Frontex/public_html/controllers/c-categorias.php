<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

class Categorias {
    private $pdo;
    private $tabela;
    private $empresa;

    public function __construct($pdo, $tabela = 'categorias') {
        $this->pdo = $pdo;
        $this->tabela = $tabela;
        $this->empresa = new Empresa();
    }

    public function buscaCategorias() {
        $stmt = $this->pdo->prepare('
        SELECT c.ID,c.nome,c.img,c.ativo FROM categorias c
          JOIN produtos p ON c.ID = p.ID_categoria
          JOIN empresa_produtos ep ON p.ID = ep.ID_produto
         WHERE p.ativo = 1 AND c.ativo = 1 AND ep.ID_empresa = :id_empresa
         GROUP BY p.ID_categoria');
         $stmt->execute([':id_empresa' => $this->empresa->getID_empresa()]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

    public function gerarCardsCategorias($dados) {
        if (empty($dados)) {
            return "<p>Nenhuma imagem disponível para o carrossel.</p>";
        }
        
        $carouselItems = '';
        $totalCards = count($dados);
        $cardsPerSlide = 4;
        
        // Inicializar o contador de cards
        $cardCount = 0;
    
        foreach ($dados as $index => $view) {
            $imagemPath = $view->img;
            $nome = $view->nome;
            $isActive = ($cardCount === 0) ? 'active' : ''; 
    
            // Iniciar um novo item do carrossel a cada 4 cards
            if ($cardCount % $cardsPerSlide === 0) {
                if ($cardCount > 0) {
                    $carouselItems .= "</div></div>"; // Fechar o item anterior
                }
                $carouselItems .= "<div class='carousel-item $isActive'><div class='row'>";
            }
            
            // Adicionar o card
            $carouselItems .= "
            <div class='col-md-3'>
                <div class='card m-4' style='width: 18rem;'>
                    <div class='image-overlay-container' style='position: relative; height: 200px;'>
                        <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; object-fit: cover;'>
                        <div class='image-overlay d-flex flex-column' style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);'>
                            <h5 class='text-white text-center mt-auto'>$nome</h5>
                        </div>
                    </div>
                </div>
            </div>";
    
            $cardCount++;
        }
    
        // Fechar o último item do carrossel
        if ($cardCount > 0) {
            $carouselItems .= "</div></div>";
        }
    
        return "
            <div id='carouselcategorias' class='carousel slide' style='max-width: 1200px; max-height: 600px; margin: 0 auto;' data-bs-ride='carousel' data-aos='fade-left'>
            <div class='carousel-inner'>
                $carouselItems
            </div>
            <button class='carousel-control-prev' type='button' data-bs-target='#carouselcategorias' data-bs-slide='prev'>
                <span class='' style='background-color: blue'><i class='lni lni-arrow-left'></i></span>
            </button>
            <button class='carousel-control-next' type='button' data-bs-target='#carouselcategorias' data-bs-slide='next'>
                <span class='text-dark'><i class='lni lni-arrow-right'></i></span>
            </button>
            </div>";
    }
}

?>
