<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');
include_once realpath(__DIR__ . '/../../model/utils.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TABELA = 'empresas';
$deletado = false;
$alterado = false;
$idAlterar = null;

if (isset($_GET['alterar'])) {
    $idAlterar = (int)$_GET['alterar'];
}

function limparCNPJ($cnpj) {
    return preg_replace('/\D/', '', $cnpj);
}

if (!empty($_POST)) {
    $telefone = Utils::parsePhone($_POST['telefone']);
    $whatsapp = Utils::parsePhone($_POST['whatsapp']);
    $instagram = $_POST['instagram'];
    $facebook = $_POST['facebook'];
    $valor_minimo = Utils::parseMoney($_POST['valor_minimo']);
    $id = $_SESSION['ID_empresa'];
    
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE $TABELA SET telefone = ?, whatsapp = ?, instagram = ?, facebook = ?, valor_minimo = ? WHERE id = ?");
        $stmt->execute([$telefone, $whatsapp, $instagram, $facebook, $valor_minimo, $id]);
        echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Configura&ccedil;&otilde;s atualizadas com sucesso </div></div></div>";
    }
}

$stmt = $pdo->prepare('SELECT * FROM ' . $TABELA .' WHERE ID = ' . $_SESSION['ID_empresa']);
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
function gerarForm($nome = '', $cnpj = '', $telefone = '', $whatsapp = '', $instagram = '', $facebook = '', $valor_minimo = '', $id= '') {

    $string = "
<div class='container mt-4'>
    <form method='post'>
        <input type='hidden' name='id' value='$id'>
        <div class='row'>
            "; 
            $string .= Form::InputText([
                'size' => 6,
                'name' => 'nome',
                'label' => 'Nome',
                'attributes' => 'disabled',
                'value' => $nome,
            ]); 
            
            $string .= Form::inputText([
                'size' => 6,
                'name' => 'cnpj',
                'label' => 'CNPJ',
                'attributes' => 'disabled',
                'value' => $cnpj,
                'class' => 'cnpj',
            ]);
            
            $string .= Form::inputText([
                'size' => 6,
                'name' => 'telefone',
                'label' => 'Telefone',
                'class' => 'phone',
                'value' => $telefone,
            ]); 

            $string .= Form::inputText([
                'size' => 6,
                'name' => 'whatsapp',
                'label' => 'Whatsapp',
                'class' => 'phone',
                'value' => $whatsapp,
            ]); 

            $string .= Form::inputText([
                'size' => 6,
                'name' => 'instagram',
                'label' => 'Instagram',
                'value' => $instagram,
            ]); 

            $string .= Form::inputText([
                'size' => 6,
                'name' => 'facebook',
                'label' => 'Facebook',
                'value' => $facebook,
            ]); 

            $string .= Form::inputMoney([
                'size' => 12,
                'name' => 'valor_minimo',
                'label' => 'Valor M&iacute;nimo de Pedido',
                'value' => $valor_minimo,
            ]); 

            $string .= "
        </div>
        
        <div class='row mt-3'>
            <div class='col-12 text-end'>
                <button type='submit' class='btn' id='btn_submit_empresas_$id' name='enviar' style = 'background-color:#003399; color: white;'>Salvar</button>
            </div>
        </div>
    </form>
</div>
";
    return $string;
}

?>

<?php
function gerarFormAtualizado($dados) {
    $form = "";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
        $cnpj = $view->cnpj;
        $telefone = $view->telefone;
        $whatsapp = $view->whatsapp;
        $instagram = $view->instagram;
        $facebook = $view->facebook;
        $valor_minimo = $view->valor_minimo;

        $form .= gerarForm($nome, $cnpj, $telefone, $whatsapp, $instagram, $facebook, $valor_minimo, $id);
    }
    
    return $form;
}
?>
