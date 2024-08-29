<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

class Categorias {
    private $pdo;
    private $tabela;
    private $empresa;

    public function __construct($pdo, $tabela = 'categorias') {
        $this->pdo = $pdo;
        $this->tabela = $tabela;
        $this->empresa = new SessaoEmpresa();
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
            return "<p>Nenhuma imagem disponível para o slide.</p>";
        }
        
        $string = '<swiper-container class="mySwiper" navigation = "true" slides-per-view="6">';
        $totalCards = count($dados);
    
        foreach ($dados as $index => $view) {
            $imagemPath = $view->img;
            $nome = $view->nome;

            $string .= "
             <swiper-slide>
                <div class='image-overlay-container' style='position: relative; height: 200px;'>
                    <a href='#'>
                        <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; width: 300px; object-fit: cover;'>
                        <div  class='image-overlay d-flex flex-column' style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);'>
                            <h5 class='text-white text-center mt-auto'>$nome</h5>
                        </div>
                    </a>
                </div>
             </swiper-slide>";
    
        }
    
        // Fechar o último item do carrossel
            $string .= "</swiper-container>";
    
        return $string;
    }
}

?>
