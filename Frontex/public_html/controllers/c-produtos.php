<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Produtos {
    private $pdo;
    private $tabela = 'produtos';
    private $empresa;

    public function __construct() {
        $this->pdo = $this->conectar();
        $this->empresa = new SessaoEmpresa();
    }

    private function conectar() {
        return conectar();
    }

    public function buscaProdutos($termoPesquisa = '') {
        $sql = '
            SELECT p.ID, p.nome, p.img, p.descricao, p.preco_venda, p.preco_promocao, p.ID_categoria, p.ativo, ep.ID_empresa 
            FROM ' . $this->tabela . ' p
            JOIN empresa_produtos ep ON p.ID = ep.ID_produto
            WHERE ep.ID_empresa = :id_empresa';
        
        if (!empty($termoPesquisa)) {
            $sql .= ' AND (p.nome LIKE :termo OR p.descricao LIKE :termo)';
        }
    
        $stmt = $this->pdo->prepare($sql);
    
        $params = [':id_empresa' => $this->empresa->getID_empresa()];
        if (!empty($termoPesquisa)) {
            $params[':termo'] = '%' . $termoPesquisa . '%';
        } 
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }
    

    private static function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    public function buscaProdutosPorCategoria($categoriaId) {
        $stmt = $this->pdo->prepare('
            SELECT p.ID, p.nome, p.img, p.descricao, p.preco_venda, p.preco_promocao, p.ID_categoria, p.ativo, ep.ID_empresa 
            FROM ' . $this->tabela . ' p
            JOIN empresa_produtos ep ON p.ID = ep.ID_produto
            WHERE ep.ID_empresa = :id_empresa AND p.ID_categoria = :categoria_id AND p.ativo = 1');
        
        $stmt->execute([
            ':id_empresa' => $this->empresa->getID_empresa(),
            ':categoria_id' => $categoriaId
        ]);
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }
    
    public static function gerarCardProdutos($dados) {
        $string = '';
        if (empty($dados)) {
            return "<div class='text-center'>Nenhum produto encontrado.</div>";
        }
        $i=0; foreach ($dados as $view) {
            $ID = $view->ID;
            $imagemPath = $view->img;
            $nome = $view->nome;
            $preco_venda = self::formatarDinheiro($view->preco_venda);
            $preco_promocao = $view->preco_promocao > 0 ? self::formatarDinheiro($view->preco_promocao) : 0;
            $aosDelay = $i . '00';
            $i++;
            if ($preco_promocao > 0) {
                $string .= "
                <div class='col'>
                    <div class='card m-3' style='width: 18rem;' data-aos='fade-up' data-aos-delay='$aosDelay'>
                        <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; object-fit: cover;'>
                        <div class='card-body d-flex flex-column justify-content-between' style='height: 150px;'>
                            <h5 class='card-title text-center'> $nome </h5>
                            <p class='card-text text-center'> De <s class = 'text-danger'>$preco_venda </s> </p>
                            <p class='card-text text-center'> Por apenas <b class = 'text-success'>$preco_promocao</b> </p> 
                        </div>";
                        if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1){
                        $string.="
                        <button class='btn btn-outline-primary rounded btn-comprar' data-id='$ID'>
                                Comprar
                        </button>
                    </div>
                </div>"; 
                        }else{
                            $string.="
                            <button class='btn btn-outline-primary rounded' data-bs-toggle='modal' data-bs-target='#modalLogar'>
                                    Comprar
                            </button>
                        </div>
                    </div>";  
                        }
            } else {
                $string .= "
                <div class='col'>
                    <div class='card m-3' style='width: 18rem;' data-aos='fade-up' data-aos-delay='$aosDelay'>
                            <img src='$imagemPath' class='card-img-top img-fluid' style='height: 200px; object-fit: cover;'>
                            <div class='card-body d-flex flex-column justify-content-between' style='height: 150px;'>
                                <h5 class='card-title text-center'>$nome</h5>
                                <p class='card-text text-center'><b class = 'text-success'>$preco_venda</b></p>
                            </div>";

                        if(isset($_SESSION['logged']) && $_SESSION['logged'] == 1){
                        $string.="
                        <button class='btn btn-outline-primary rounded btn-comprar' data-id='$ID'>
                                Comprar
                        </button>
                    </div>
                </div>"; 
                        }else{
                            $string.="
                            <button class='btn btn-outline-primary rounded' data-bs-toggle='modal' data-bs-target='#modalLogar'>
                                    Comprar
                            </button>
                        </div>
                    </div>";  
                        }
            }
        }

        return $string;
    }
}
?>
