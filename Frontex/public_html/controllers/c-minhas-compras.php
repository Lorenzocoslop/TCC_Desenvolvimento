<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
require '../../model/path.php';
include_once realpath(__DIR__ . '/../model/form.class.php');

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
        SELECT pe.ID, pp.ID_produto,p.nome,pe.nome as nome_pedido, p.img,p.descricao,p.preco_venda,p.preco_promocao,pp.qtd,pe.status,cu.valor_desc,pe.endereco,pe.bairro,pe.cidade,pe.estado,pe.complemento,pe.cep,pe.numero FROM '. $this->tabela .' pe
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
                            <a href='FormModal$ID_pedido' data-bs-toggle='modal' data-bs-target='#formModal$ID_pedido' class='btn btn-primary'>Ver Detalhes</a>
                        </div>
                    </div>"; 
        }

        

        return $string;
    }
}

$compras = new MinhasCompras();
$obj = $compras->buscaProdutos();

function formatarPrecoParaTela($valor) {
    return number_format((float)$valor, 2, ',', '');
}

function gerarModalForm($pdo, $idModal, $titulo, $nome = '', $endereco = '', $bairro = '', $numero = '', $cidade = '', $estado = '', $cep = '', $complemento = '',$id= '') {
    $stmt = $pdo->prepare('SELECT p.ID,p.nome,p.img,p.preco_venda,p.preco_promocao,p.preco_venda,p.ID_categoria, p.ativo, pp.ID_produto, pp.ID_pedido, pp.qtd FROM pedidos_produtos pp
                                 JOIN produtos p ON p.ID = pp.ID_produto
                                WHERE ID_pedido = '.$id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $produtos_pedido = array_map(function($view) {
            return (object) $view; 
        }, $result);
    $total = 0;
    $string = "
<div class='modal fade' id='$idModal' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='{$idModal}Label' aria-hidden='true'>
    <form method='post' style='margin-right: 500px;'> 
        <div class='modal-dialog'>
            <div class='modal-content' style='width:1000px;'>
            <input type='hidden' value='$id'> 
                <div class='modal-header bg-secondary text-white text-center'>
                    <h1 class='modal-title fs-5 w-100 text-center' id='{$idModal}Label'>$titulo</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='row'>
                    
                        "; 
                        $string .= Form::InputText([
                            'size' => 12,
                            'name' => 'nome',
                            'label' => 'Nome',
                            'value' => $nome,
                            'attributes' => 'disabled',
                        ]); 
                        
                        $string.= Form::InputText([
                            'size' => 6,
                            'name' => 'endereco',
                            'id' => 'endereco',
                            'label' => 'Rua',
                            'value' => $endereco,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'bairro',
                            'id' => 'bairro',
                            'label' => 'Bairro',
                            'value' => $bairro,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 2,
                            'name' => 'numero',
                            'id' => 'numero',
                            'label' => 'N&uacute;mero',
                            'value' => $numero,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'cidade',
                            'id' => 'cidade',
                            'label' => 'Cidade',
                            'value' => $cidade,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'estado',
                            'id' => 'estado',
                            'label' => 'Estado',
                            'value' => $estado,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'cep',
                            'id' => 'cep',
                            'label' => 'CEP',
                            'class' => 'cep',
                            'value' => $cep,
                            'attributes' => 'disabled',
                        ]);
    
                        $string.= Form::InputText([
                            'size' => 12,
                            'name' => 'complemento',
                            'id' => 'complemento',
                            'label' => 'Complemento',
                            'value' => $complemento,
                            'attributes' => 'disabled',
                        ]);

                        $string .= '
                        <div class="accordion" id="formAccordion"> 
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Produtos
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#formAccordion"> 
                                    <div class="accordion-body">
                                    ';
                                    foreach($produtos_pedido as $produto) {
                                        if($produto->preco_promocao > 0){
                                            $string .= '
                                            <div class="row">
                                                <div class="col col-md-6 text-center">
                                                    <img src = "'.$produto->img.'" style="width:15rem; height:10rem;">
                                                </div>
                                                <div class="col col-md-3 text-center" style="word-wrap: break-word;">
                                                    <small>Produto</small>
                                                    <h3>'.$produto->nome.'</h3>
                                                </div>
                                                <div class="col col-md-3 text-center" style="word-wrap: break-word;">
                                                    <small>Preço</small>
                                                    <h3>R$ '.formatarPrecoParaTela($produto->preco_promocao).'</h3>
                                                </div>
                                            </div>';
                                            $total += $produto->preco_promocao;
                                        } else {
                                            $string .= '
                                            <div class="row">
                                                <div class="col col-md-6 text-center">
                                                    <img src = "'.$produto->img.'" style="width:15rem; height:10rem;">
                                                </div>
                                                <div class="col col-md-3 text-center" style="word-wrap: break-word;">
                                                    <small>Produto</small>
                                                    <h3>'.$produto->nome.'</h3>
                                                </div>
                                                <div class="col col-md-3 text-center" style="word-wrap: break-word;">
                                                    <small>Preço</small>
                                                    <h3>R$ '.formatarPrecoParaTela($produto->preco_venda).'</h3>
                                                </div>
                                            </div>';
                                            $total += $produto->preco_venda;
                                        }
                                        
                                    }
                        $string .= '    
                                    </div>
                                </div>
                            </div>
                        </div>';
                        $string .=
                         "
                    </div>
                    <div class='modal-footer' style='background-color: white'>
                        <small><strong>Total</strong></small>
                        <h3>R$ ".formatarPrecoParaTela($total)."</h3>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
";
    return $string;
}

function gerarModaisTabela($dados) {
    $modais = "";
    $pdo = conectar();

    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome_pedido; 
        $endereco = $view->endereco;
        $bairro = $view->bairro;
        $numero = $view->numero;
        $cidade = $view->cidade;
        $estado = $view->estado;
        $cep = $view->cep;
        $complemento = $view->complemento;

        $modais .= gerarModalForm($pdo,"formModal$id", "Detalhes do Pedido #$id", $nome, $endereco, $bairro, $numero, $cidade, $estado, $cep, $complemento, $id);
    }
    
    return $modais;
}
?>
