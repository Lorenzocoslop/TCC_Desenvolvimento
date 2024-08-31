<?php
include_once realpath(__DIR__ . '/../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');
include_once realpath(__DIR__ . '/../../model/utils.class.php');
include_once '../../model/empresa.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TABELA = 'cupons';
$deletado = false;
$alterado = false;
$idAlterar = null;

if (isset($_GET['alterar'])) {
    $idAlterar = (int)$_GET['alterar'];
}

if (!empty($_POST)) {
    $nome = $_POST['nome'];
    $dt_fim = $_POST['dt_fim'];
    $ID_empresa = $_POST['ID_empresa'];
    $quant_usos = $_POST['quant_usos'];
    $id = $_POST['id'] ?? null;
    
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, dt_fim = ?, ID_empresa = ?, quant_usos = ? WHERE id = ?");
        $stmt->execute([$nome, $dt_fim, $ID_empresa, $quant_usos, $id]);
        echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Cupom atualizado com sucesso </div></div></div>";
    } else {
            $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, dt_fim, ID_empresa, quant_usos) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $dt_fim, $ID_empresa, $quant_usos]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Cupom inserido com sucesso </div></div></div>";
    }
}

if($_SESSION['ID_empresa'] == 0){
    $stmt = $pdo->prepare('SELECT * FROM ' . $TABELA);
} else {
    $stmt = $pdo->prepare('SELECT * FROM ' . $TABELA . ' WHERE ID_empresa = '. $_SESSION['ID_empresa']);
    }


$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$obj = array_map(function($view) {
    return (object) $view; 
}, $result);

if ($idAlterar) {
    $stmt = $pdo->prepare("SELECT * FROM $TABELA WHERE id = ?");
    $stmt->execute([$idAlterar]);
    $registro = $stmt->fetch(PDO::FETCH_ASSOC);
}
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

$dados = 
[
    'Nome',
    'Data Fim',
];
?>

<?php
function gerarTbody($dados) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
        $dt_fim = $view->dt_fim;
        $data = new DateTime($dt_fim);
        $ativo = $view->ativo;

        $tbody .= "<tr data-id='$id'>";
        $tbody .= "<td scope='row' class='text-center'>
            <a href='#formModal$id' class = 'text-dark' data-bs-toggle='modal' data-bs-target='#formModal$id'>$id - $nome <i class='lni lni-pencil'></i></a>
        </td>";
        $tbody .= "
        <td scope='row' class='text-center' id='dataExpiracao_$id'>
            ". date_format($data,'d/m/Y') ."
        </td>";
        $tbody .= "<td class='text-end' id='row_ativo'>
            <button type='button' class='btn ativo' id='ativo_$id' value = '$ativo'>
                <i class='lni lni-checkmark-circle text-success'></i>
            </button>

            <button type='button' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#staticBackdrop$id'>
                <i class='lni lni-trash-can w-100 h-100'></i>
            </button>
        </td>"; 
        $tbody .= "</tr>";
    }
    
    $tbody .= "</tbody>";
    
    return $tbody;
}
?>

<?php
function gerarModaisTabela($dados) {
    $modais = "";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
        $dt_fim = $view->dt_fim;
        $data = new DateTime($dt_fim);
        $quant_usos = $view->quant_usos;
        $ID_empresa = $view->ID_empresa;

        $modais .= gerarModalForm("formModal$id", "Alterar Empresa", $nome, $dt_fim, $ID_empresa, $quant_usos, $id);
        $modais .= gerarModalDelete($view); 
    }
    
    return $modais;
}
?>

<?php
function gerarModalForm($idModal, $titulo, $nome = '', $dt_fim = '', $ID_empresa = '', $quant_usos ='', $id= '') {
    global $pdo;

    $empresas = new Empresa($pdo);
    $listaempresas = $empresas->searchEmpresas();

    $options = ['0' => 'Selecione'];
    foreach ($listaempresas as $empresa) {
        $options[$empresa->ID] = $empresa->nome;
    }

    $string = "
<div class='modal fade' id='$idModal' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='{$idModal}Label' aria-hidden='true'>
    <form method='post' enctype='multipart/form-data'> 
    <input type='hidden' name='id' value='$id'>  
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header bg-secondary text-white text-center'>
                    <h1 class='modal-title fs-5 w-100 text-center' id='{$idModal}Label'>$titulo</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='row'>
                    
                        "; 
                        if($_SESSION['nivel'] < 4){
                        $string .= Form::InputText([
                            'size' => 12,
                            'name' => 'nome',
                            'label' => 'Nome',
                            'value' => $nome,
                            'required' => true,
                        ]); 
                        }

                        if($_SESSION['nivel'] == 4){
                            $string .= Form::InputText([
                                'size' => 6,
                                'name' => 'nome',
                                'label' => 'Nome',
                                'value' => $nome,
                                'required' => true,
                            ]); 
                            }

                        if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 4){
                            $string .= Form::select([
                                'size' => 6,
                                'name' => 'ID_empresa',
                                'label' => 'Empresa',
                                'options' => $options,
                                'value' => $ID_empresa,
                            ]);
                        }
                        
                        $string .= Form::inputDate([
                            'size' => 6,
                            'name' => 'dt_fim',
                            'label' => 'Data Fim',
                            'value' => $dt_fim,
                            'required' => true,
                        ]); 

                        $string .= Form::inputText([
                            'size' => 6,
                            'name' => 'quant_usos',
                            'label' => 'Quantidade de usos',
                            'value' => $quant_usos,
                        ]); 
                        $string .=
                         "
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                    <button type='submit' class='btn btn-primary' id='btn_submit_cupons_$id' name='enviar'>Salvar</button>
                </div>
            </div>
        </div>
    </form>
</div>
";
    return $string;
}

function gerarModalDelete($view) {
    $titulo = 'Deletar';
    $descricao = 'Deseja realmente deletar este registro?';
    $botaoFechar = 'Voltar';
    $botaoDeletar = 'Deletar';
    $id = htmlspecialchars($view->ID, ENT_QUOTES, 'UTF-8'); 
    return "
    <div class='modal fade' id='staticBackdrop$id' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdrop{$id}Label' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='staticBackdrop{$id}Label'>$titulo</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    $descricao
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>$botaoFechar</button>
                    <a href='?delete=$id' id='btn_delete_$id' class='btn btn-danger'>$botaoDeletar</a>
                </div>
            </div>
        </div>
    </div>
    ";
}

?>
