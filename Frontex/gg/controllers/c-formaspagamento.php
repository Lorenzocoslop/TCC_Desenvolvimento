<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

$TABELA = 'formaspagamento';
$deletado = false;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_empresa = $_SESSION['ID_empresa'];


$stmt = $pdo->prepare('SELECT * FROM ' . $TABELA);
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
        $thead .= "<th scope='col' class='text-start'>{$dados}</th>";
    }

    $thead .= "</tr></thead>";

    return $thead;
}

$dados = ['Formas'];
?>

<?php
function gerarTbody($dados, $pdo, $id_empresa) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $id_formapagamento = $view->ID;
        $nome = $view->nome;

        $stmt_check = $pdo->prepare('SELECT COUNT(*) FROM formaspagamento_empresas WHERE ID_empresa = ? AND ID_formapagamento = ?');
        $stmt_check->execute([$id_empresa, $id_formapagamento]);
        $checked = $stmt_check->fetchColumn() > 0 ? 'checked' : '';

        $tbody .= "<tr>";
        $tbody .= "<td scope='row' class='text-start'>
            <input type='checkbox' class='checkbox-formapagamento' id='formapagamento_$id_formapagamento' value='$id_formapagamento' $checked> $nome
        </td>";
        $tbody .= "</tr>";
    }
    
    $tbody .= "</tbody>";
    
    return $tbody;
}
?>
