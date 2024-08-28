<?php
include_once realpath(__DIR__ . '/../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

$TABELA = 'empresas';
$deletado = false;
$alterado = false;
$idAlterar = null;

function exibirMensagem($mensagem, $tipo = 'error') {
    echo "<div class='status-top-right text-center' id='status-container'><div class='status status-$tipo'><div class='status-message'>$mensagem</div></div></div>";
}

if (isset($_GET['alterar'])) {
    $idAlterar = (int)$_GET['alterar'];
}

function limparCNPJ($cnpj) {
    return preg_replace('/\D/', '', $cnpj);
}

if (!empty($_POST)) {
    $nome = $_POST['nome'];
    $cnpj = limparCNPJ($_POST['cnpj']);
    $id = $_POST['id'] ?? null;
    
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, cnpj = ? WHERE id = ?");
        $stmt->execute([$nome, $cnpj, $id]);
        echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Empresa atualizada com sucesso </div></div></div>";
    } else {
            $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, cnpj) VALUES (?, ?)");
            $stmt->execute([$nome, $cnpj]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Empresa inserida com sucesso </div></div></div>";
    }
}

$stmt = $pdo->prepare('SELECT * FROM ' . $TABELA);
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
    'CNPJ',
];
?>

<?php
function gerarTbody($dados) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
        $cnpj = $view->cnpj;
        $ativo = $view->ativo;

        $tbody .= "<tr>";
        $tbody .= "<td scope='row' class='text-start'>
            <a href='#formModal$id' class = 'text-dark' data-bs-toggle='modal' data-bs-target='#formModal$id'>$id - $nome <i class='lni lni-pencil'></i></a>
        </td>";
        $tbody .= "
        <td scope='row' id= 'row_cnpj' class='text-center cnpj'>
            $cnpj
        </td>";
        $tbody .= "<td class='text-end' id='row_ativo'>
            <button type='button' class='btn' id='ativo_$id' value = '$ativo'>
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
        $cnpj = $view->cnpj;

        $modais .= gerarModalForm("formModal$id", "Alterar Empresa", $nome, $cnpj, $id);
        $modais .= gerarModalDelete($view); 
    }
    
    return $modais;
}
?>

<?php
function gerarModalForm($idModal, $titulo, $nome = '', $cnpj = '', $id= '') {

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
                        $string .= Form::InputText([
                            'size' => 12,
                            'name' => 'nome',
                            'label' => 'Nome',
                            'value' => $nome,
                            'required' => true,
                        ]); 
                        
                        $string .= Form::inputText([
                            'size' => 12,
                            'name' => 'cnpj',
                            'label' => 'CNPJ',
                            'value' => $cnpj,
                            'class' => 'cnpj',
                            'required' => true,
                        ]); 
                        $string .=
                         "
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                    <button type='submit' class='btn btn-primary' id='btn_submit_empresas_$id' name='enviar'>Salvar</button>
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
