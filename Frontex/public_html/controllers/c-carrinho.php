<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class Carrinho {
    private $pdo;
    private $tabela = 'carrinho';
    private $empresa;
    private $produtos;
    

    public function __construct() {
        $this->pdo = $this->conectar();
        $this->empresa = new SessaoEmpresa();
        $this->produtos = new Produtos();
    }

    private function conectar() {
        return conectar();
    }

    public function buscaPedidos() {
        $stmt = $this->pdo->prepare('
        SELECT cp.ID_produto,p.nome, p.img,p.descricao,p.preco_venda,p.preco_promocao,cp.qtd,cu.valor_desc FROM '. $this->tabela .' c
          JOIN carrinho_produtos cp ON c.ID = cp.ID_carrinho
          JOIN usuarios u ON u.ID = c.ID_usuario
          JOIN produtos p ON cp.ID_produto = p.ID
          LEFT JOIN cupons cu ON cu.ID = c.ID_cupom
         WHERE c.ID_usuario = :id_usuario');
        $stmt->execute([':id_usuario' => $_SESSION['ID']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

    public function contaPedidos() {
        $stmt = $this->pdo->prepare('
        SELECT Count(cp.ID_produto) as qtd FROM '. $this->tabela .' c
          JOIN carrinho_produtos cp ON c.ID = cp.ID_carrinho
          JOIN usuarios u ON u.ID = c.ID_usuario
          JOIN produtos p ON cp.ID_produto = p.ID
         WHERE c.ID_usuario = :id_usuario');
        $stmt->execute([':id_usuario' => $_SESSION['ID']]);
        return $stmt->fetchColumn(); 
    }

    private static function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    public static function listarProdutos($dados) {
        $string = '';
        if (empty($dados)) {
            return "<div class='text-center m-3'>Nenhum produto no carrinho.</div>";
        }
        foreach ($dados as $view) {
            $ID_produto = $view->ID_produto;
            $imagemPath = $view->img;
            $nome = $view->nome;
            $qtd = $view->qtd;
            $preco_venda = self::formatarDinheiro($view->preco_venda);
            $preco_promocao = $view->preco_promocao > 0 ? self::formatarDinheiro($view->preco_promocao) : 0;
            $diferenca = abs($view->preco_venda - $view->preco_promocao);
            $desc = floor(($diferenca / $view->preco_venda) * 100);
            $disableDecrease = $qtd == 1 ? 'disabled' : '';

            if ($preco_promocao > 0) {
                $string .= "
                <div class = 'row p-4' id='itemProduto_$ID_produto'>
                    <div class = 'col col-md-4 text-start'>
                        <div class = 'row'>
                            <img src = '$imagemPath' style='width:15rem; height:10rem;'>
                        </div>
                        
                    </div>
                    <div class = 'col col-md-4'>
                        <div class = 'row row-md-6'>
                            <small class= 'text-center'>Produto</small>
                            <p class= 'text-center' style='word-wrap: break-word;'>$nome</p>
                        </div>
                        <div class='row row-md-6 text-start mt-5'>
                            <a class='btn btn-outline-danger w-50 btn-excluir' data-id='$ID_produto'><i class='lni lni-trash-can'></i> Excluir</a>
                        </div>
                    </div>
                    <div class = 'col col-md-4'>
                        <div class = 'row row-md-6'>
                            <small class= 'text-center'>Preço</small>
                            <p style='word-wrap: break-word;' class= 'text-center'><s class= 'text-danger'>$preco_venda</s> - <strong> $preco_promocao</strong></p>
                            <small class= 'text-center text-success'><strong>$desc% de desconto</strong></small>
                        </div>
                        <div class='row row-md-6 d-flex justify-content-end mt-4'>
                        <div class='input-group quantity-container ' style='max-width: 150px;'>
                            <div class='input-group-prepend'>
                                <button class='btn btn-outline-secondary btn-quantity-decrease' type='button' id='decrease_$ID_produto' data-id='$ID_produto' $disableDecrease>-</button>
                            </div>
                            <input type='text' class='form-control text-center' id='quantity_$ID_produto' value='$qtd' min='1'>
                            <div class='input-group-append'>
                                <button class='btn btn-outline-secondary btn-quantity-increase' type='button' id='increase_$ID_produto' data-id='$ID_produto'>+</button>
                            </div>
                        </div>
                    </div>      
                    </div>
                </div>"; 
            } else {
                $string .= "
                <div class = 'row p-4' id='itemProduto_$ID_produto'>
                    <div class = 'col col-md-4 text-start'>
                        <div class = 'row'>
                            <img src = '$imagemPath' style='width:15rem; height:10rem;'>
                        </div>
                        
                    </div>
                    <div class = 'col col-md-4'>
                        <div class = 'row row-md-6'>
                            <small class= 'text-center'>Produto</small>
                            <p class= 'text-center'>$nome</p>
                        </div>
                        <div class='row row-md-6 text-start mt-4'>
                            <a class='btn btn-outline-danger w-50 btn-excluir' data-id='$ID_produto'><i class='lni lni-trash-can'></i> Excluir</a>
                        </div>
                    </div>
                    <div class = 'col col-md-4 text-start'>
                        <div class = 'row row-md-6'>
                            <small class= 'text-center'>Preço</small>
                            <p style='word-wrap: break-word;' class= 'text-center'> <strong>$preco_venda</strong></p>
                        </div>
                        <div class='row row-md-6 d-flex justify-content-end mt-4'>
                        <div class='input-group quantity-container ' style='max-width: 150px;'>
                            <div class='input-group-prepend'>
                                <button class='btn btn-outline-secondary btn-quantity-decrease' type='button' id='decrease_$ID_produto' data-id='$ID_produto' $disableDecrease>-</button>
                            </div>
                            <input type='text' class='form-control text-center' id='quantity_$ID_produto' value='$qtd' min='1'>
                            <div class='input-group-append'>
                                <button class='btn btn-outline-secondary btn-quantity-increase' type='button' id='increase_$ID_produto' data-id='$ID_produto'>+</button>
                            </div>
                        </div>
                    </div>      
                    </div>
                </div>"; 
            }
        }

        return $string;
    }

    public static function gerarTotalProdutos($dados) {
        $string='';
        $pdo = conectar();
        $stmt = $pdo->prepare('
            SELECT 
                cp.ID_produto, 
                p.preco_venda, 
                p.preco_promocao, 
                cp.qtd, 
                cu.valor_desc 
            FROM 
                carrinho_produtos cp
                JOIN produtos p ON cp.ID_produto = p.ID
                LEFT JOIN cupons cu ON cu.ID = (SELECT ID_cupom FROM carrinho WHERE ID_usuario = :id_usuario)
            WHERE 
                cp.ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)
        ');

        $stmt->execute([':id_usuario' => $_SESSION['ID']]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalsemcupom = 0;
        $totalcomcupom = 0;
        $valor_desc = 0;

        foreach ($dados as $view) {
            $qtd = $view['qtd'];
            $preco_venda = $view['preco_venda'];
            $preco_promocao = $view['preco_promocao'];
            $valor_desc = $view['valor_desc'];

            if ($preco_promocao > 0) {
                $totalsemcupom += $preco_promocao * $qtd;
            } else {
                $totalsemcupom += $preco_venda * $qtd;
            }
        }

        $totalcomcupom = $totalsemcupom - ($totalsemcupom * $valor_desc / 100);

        $totalsemcupomFormatado = self::formatarDinheiro($totalsemcupom);
        $totalcomcupomFormatado = self::formatarDinheiro($totalcomcupom);

        $string.="
        <div class='bg-light m-5 rounded p-4 mx-4'>
            <h2 class = 'text-center'>Resumo da compra</h2>
            <div class = 'row p-4 text-center'>
                <div class='col col-md-6'> 
                    <h5>Produtos</h5>
                </div>
                <div class='col col-md-6'> 
                    <h5 id='totalsemcupom'><strong>$totalsemcupomFormatado</strong></h5>
                </div>
            </div>
            <div class = 'row p-4 text-center'>
                <div class='col col-md-7'> 
                    <a href='#'>Insira um cupom</a>
                </div>
            </div>
            <div class = 'row p-4 text-center'>
                <div class='col col-md-6'> 
                    <h5>Preço Total</h5>
                </div>
                <div class='col col-md-6'> 
                    <h5 id='totalcomcupom'><strong>$totalcomcupomFormatado</strong></h5>
                </div>
            </div>
            <div class = 'row p-4 text-center'>
                <a href='../view/v-finalizar-compra.php' class='btn btn-outline-primary rounded'>
                    Finalizar Compra
                </a>
            </div>    
        </div>";

    return $string;
        
    } 
}
?>
