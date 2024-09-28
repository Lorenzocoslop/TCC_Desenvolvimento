<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

$TABELA = 'pedidos';
$deletado = false;

$pdo = conectar();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_empresa = $_SESSION['ID_empresa'];


$stmt = $pdo->prepare('SELECT * FROM ' . $TABELA .'
                        WHERE ID_empresa = '.$id_empresa);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$obj = array_map(function($view) {
    return (object) $view; 
}, $result);
?>

<?php
function gerarThead($dados)
{
    $thead = "<thead><tr>";

    foreach ($dados as $dados) {
        $thead .= "<th scope='col' class='text-center'>{$dados}</th>";
    }

    $thead .= "</tr></thead>";

    return $thead;
}

$dados = [
    'Cliente',
    'Status'
];
?>

<?php
function gerarTbody($dados) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $ID = $view->ID;
        $nome = $view->nome;
        $status = $view->status;

        $tbody .= "<tr>";
        $tbody .= "<td scope='row' class='text-start'>$nome</td>";
        $tbody .= "<td scope='row' id='row_status_$ID' class='text-center'>";
        if($status == 1) {
            $tbody .= "<a class='btn btn-success' id='btn_status_$ID' data-id='$ID' data-status='aceitar'>Aceitar</a>
                       <a class='btn btn-danger' id='btn_status_$ID' data-id='$ID' data-status='recusar'>Recusar</a>";
        } elseif($status == 2) {
            $tbody .= "<a class='btn btn-warning' id='btn_status_$ID' data-id='$ID' data-status='finalizar'>Finalizar</a>
                       <a class='btn btn-danger' id='btn_status_$ID' data-id='$ID' data-status='recusar'>Recusar</a>";
        } elseif($status == 3) {
            $tbody .= "<p class='text-success'>Finalizado</p>";
        } elseif($status == 4) {
            $tbody .= "<p class='text-danger'>Cancelado</p>";
        }
        
        $tbody .= "</td>";
        $tbody .= "<td scope='row' class='text-end'>
                    <a href='#formModal$ID' data-bs-toggle='modal' class='text-decoration-none btn btn-primary' data-bs-target='#formModal$ID'><i class='lni lni-search-alt'></i> Ver Detalhes</a>
                    </td>";

        $tbody .= "</tr>";
    }
    
    $tbody .= "</tbody>";
    
    return $tbody;
}


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
?>

<?php
function gerarModaisTabela($dados) {
    $modais = "";
    $pdo = conectar();

    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
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
