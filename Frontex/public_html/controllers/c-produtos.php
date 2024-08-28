<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

class Produtos {
    private $pdo;
    private $tabela = 'produtos';
    private $empresa;

    public function __construct() {
        $this->pdo = $this->conectar();
        $this->empresa = new Empresa();
    }

    private function conectar() {
        return conectar();
    }

    public function buscaProdutos() {
        $stmt = $this->pdo->prepare('
        SELECT * FROM '. $this->tabela .' p
          JOIN empresa_produtos ep ON p.ID = ep.ID_produto
         WHERE ID_empresa = :id_empresa');
        $stmt->execute([':id_empresa' => $this->empresa->getID_empresa()]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

    private static function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    public static function gerarCardProdutos($dados) {
        $string = '';
        if (empty($dados)) {
            return "<div class='text-center'>Nenhum produto.</div>";
        }
        foreach ($dados as $view) {
            $imagemPath = $view->img;
            $nome = $view->nome;
            $preco_venda = self::formatarDinheiro($view->preco_venda);
            $preco_promocao = $view->preco_promocao > 0 ? self::formatarDinheiro($view->preco_promocao) : 0;

            if ($preco_promocao > 0) {
                $string .= "
                <div class='col'>
                    <div class='card m-3' style='width: 18rem;' data-aos='fade-up'>
                        <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; object-fit: cover;'>
                        <div class='card-body d-flex flex-column justify-content-between' style='height: 150px;'>
                            <h5 class='card-title text-center'> $nome </h5>
                            <p class='card-text text-center'> De <s class = 'text-danger'>$preco_venda </s> </p>
                            <p class='card-text text-center'> Por apenas <b class = 'text-success'>$preco_promocao</b> </p> 
                        </div>
                        <a href='#' style='text-decoration: none;'>
                                <div class = 'text-center bg-custom' style = 'padding: 10px;'>
                                    Comprar
                                </div>
                            </a>
                    </div>
                </div>"; 
            } else {
                $string .= "
                <div class='col'>
                    <div class='card m-3' style='width: 18rem;' data-aos='fade-up'>
                            <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; object-fit: cover;'>
                            <div class='card-body d-flex flex-column justify-content-between' style='height: 150px;'>
                                <h5 class='card-title text-center'>$nome</h5>
                                <p class='card-text text-center'><b class = 'text-success'>$preco_venda</b></p>
                            </div>
                        <a href='#' style='text-decoration: none;'>
                            <div class = 'text-center bg-custom' style = 'padding: 10px;'>
                                Comprar
                            </div>
                        </a>
                    </div>
                </div>";
            }
        }

        return $string;
    }
}
?>
