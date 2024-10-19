<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
require '../../model/path.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class MinhasCompras {
    private $pdo;
    private $tabela = 'pedidos';
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

    public function buscaProdutos() {
        $stmt = $this->pdo->prepare('
        SELECT pe.ID, pp.ID_produto,p.nome, p.img,p.descricao,p.preco_venda,p.preco_promocao,pp.qtd,pe.status,cu.valor_desc FROM '. $this->tabela .' pe
          JOIN pedidos_produtos pp ON pe.ID = pp.ID_pedido
          JOIN usuarios u ON u.ID = pe.ID_usuario
          JOIN produtos p ON pp.ID_produto = p.ID
          LEFT JOIN cupons cu ON cu.ID = pe.ID_cupom
         WHERE pe.ID_usuario = :id_usuario
         GROUP BY pe.ID
         ORDER BY pe.status');
        $stmt->execute([':id_usuario' => $_SESSION['ID']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($view) {
            return (object) $view; 
        }, $result);
    }

    private static function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    public static function listarPedidos($dados) {
        $string = '';
        if (empty($dados)) {
            return "<div class='text-center m-3'>Nenhuma compra realizada.</div>";
        }
        foreach ($dados as $view) {
            $ID_pedido = $view->ID;
            $ID_produto = $view->ID_produto;
            $imagemPath = $view->img;
            $nome = $view->nome;
            $status = $view->status;
            $qtd = $view->qtd;
            $preco_venda = self::formatarDinheiro($view->preco_venda);
            $preco_promocao = $view->preco_promocao > 0 ? self::formatarDinheiro($view->preco_promocao) : 0;
            $diferenca = abs($view->preco_venda - $view->preco_promocao);
            $desc = floor(($diferenca / $view->preco_venda) * 100);

                $string .= "
                    <div class='d-flex align-items-center m-3 border rounded p-3' id='itemPedido_$ID_pedido'>
                        
                       <div class='me-auto'>
                            <small>Número do Pedido</small>
                            <p class='mb-0 fw-bold'>Pedido #$ID_pedido</p>
                        </div>";
                if($status == 1){
                    $string .= "
                        <div class='me-auto'>
                            <small>Status</small>
                            <p class='mb-0 fw-bold'>Enviado</p>
                        </div>";
                } elseif($status == 2){
                    $string .= "
                        <div class='me-auto'>
                            <small>Status</small>
                            <p class='mb-0 fw-bold'>Aceito</p>
                        </div>";
                } elseif($status == 3){
                    $string .= "
                        <div class='me-auto'>
                            <small>Status</small>
                            <p class='mb-0 fw-bold'>Finalizado</p>
                        </div>";
                } elseif($status == 4){
                    $string .= "
                        <div class='me-auto'>
                            <small>Status</small>
                            <p class='mb-0 fw-bold'>Cancelado</p>
                        </div>";
                }
                
                $string .= "
                        <div>
                            <a href='#' class='btn btn-primary'>Ver Detalhes</a>
                        </div>
                    </div>"; 
        }

        return $string;
    }
}
?>
