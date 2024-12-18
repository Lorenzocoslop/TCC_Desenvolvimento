<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once '../../model/empresa.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TABELA = 'produtos';
$deletado = false;
$alterado = false;
$idAlterar = null;

$diretorio = '../resources/uploads/produtos/';
$diretorioAlternativo = realpath(__DIR__ . '/../../public_html/resources/uploads/produtos/'); // Defina o caminho do diretório alternativo

function copiarDiretorio($src, $dest) {

    if (!file_exists($dest)) {
        mkdir($dest, 0755, true);
    }


    $dir = opendir($src);


    $filesInDest = array();
    if ($handle = opendir($dest)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $filesInDest[] = $file;
            }
        }
        closedir($handle);
    }

    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {

            $srcFile = $src . '/' . $file;
            $destFile = $dest . '/' . $file;


            if (is_dir($srcFile)) {
                copiarDiretorio($srcFile, $destFile);
            } else {

                copy($srcFile, $destFile);
            }


            $key = array_search($file, $filesInDest);
            if ($key !== false) {
                unset($filesInDest[$key]);
            }
        }
    }

    foreach ($filesInDest as $file) {
        $filePath = $dest . '/' . $file;
        if (is_dir($filePath)) {

            removeDiretorio($filePath);
        } else {

            unlink($filePath);
        }
    }


    closedir($dir);
}

function removeDiretorio($dir) {

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $filePath = "$dir/$file";
        if (is_dir($filePath)) {
            removeDiretorio($filePath);
        } else {
            unlink($filePath);
        }
    }


    rmdir($dir);
}

if (isset($_GET['alterar'])) {
    $idAlterar = (int)$_GET['alterar'];
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT img FROM $TABELA WHERE id = ?");
    $stmt->execute([$id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($banner) {
        $imagemPath = $banner['img'];

        if (file_exists($imagemPath) && $imagemPath != '../../image/produto-sem-imagem.jpg') {
            unlink($imagemPath);
        }

        $deletado = true;


        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/produtos/'), $diretorioAlternativo);
    }
}

function formatarPrecoParaSalvar($valor) {
    $valor = trim($valor);

    $valor = preg_replace('/[^\d,\.]/', '', $valor);

    if (strpos($valor, '.') !== false && strpos($valor, ',') !== false) {
        $valor = str_replace('.', '', $valor); 
        $valor = str_replace(',', '.', $valor); 
    } elseif (strpos($valor, ',') !== false) {

        $valor = str_replace(',', '.', $valor);
    }

    // Garante que o número esteja no formato decimal com duas casas
    return number_format((float)$valor, 2, '.', '');
}

if (!empty($_POST)) {    
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $imagem = $_FILES['img'];
    $ID_categoria = $_POST['ID_categoria'];
    $imagem_sem_imagem = '../../image/produto-sem-imagem.jpg';
    $ID_empresa = $_POST['ID_empresa'] ?? $_SESSION['ID_empresa'];
    $preco_venda = formatarPrecoParaSalvar($_POST['preco_venda']);
    $preco_promocao = formatarPrecoParaSalvar($_POST['preco_promocao'] ?? 0);
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Atualização de registro existente
        $stmt = $pdo->prepare("SELECT img FROM $TABELA WHERE id = ?");
        $stmt->execute([$id]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagemAntiga = $registro['img'];

        if ($imagem['error'] === 0) {

            $diretorio = '../resources/uploads/produtos/';
            $baseUrl = $diretorio . uniqid() . '_' . basename($imagem['name']);

            if (move_uploaded_file($imagem['tmp_name'], $baseUrl)) {
                if ($imagemAntiga && file_exists($imagemAntiga)) {
                    unlink($imagemAntiga);
                }
                    $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, img = ?, descricao = ?, preco_venda = ?, preco_promocao = ?, ID_categoria = ?  WHERE id = ?");
                    $stmt->execute([$nome, $baseUrl, $descricao, $preco_venda, $preco_promocao, $ID_categoria, $id]);

                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Produto atualizado com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            // Atualização sem nova imagem
                $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, descricao = ?, preco_venda = ?, preco_promocao = ?, ID_categoria = ? WHERE id = ?");
                $stmt->execute([$nome, $descricao, $preco_venda, $preco_promocao, $ID_categoria, $id]);

            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Produto atualizado com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/produtos/'), $diretorioAlternativo);
    } else {
        // Inserção de novo registro
        if ($imagem['error'] === 0) {

            $baseUrl = $diretorio . uniqid() . '_' . basename($imagem['name']);

            if (move_uploaded_file($imagem['tmp_name'], $baseUrl)) {
                    $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, img, descricao, preco_venda, preco_promocao,ID_categoria) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nome, $baseUrl, $descricao, $preco_venda, $preco_promocao, $ID_categoria]);

                    $stmt = $pdo->query("SELECT LAST_INSERT_ID() AS ID");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id_produto = $row['ID'];
                    
                    $stmt = $pdo->prepare("INSERT INTO empresa_produtos (ID_produto,ID_empresa) VALUES (?, ?)");
                    $stmt->execute([$id_produto,$ID_empresa]);

                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Produto inserido com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
                $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, img, descricao, preco_venda, preco_promocao,ID_Categoria) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $imagem_sem_imagem, $descricao, $preco_venda, $preco_promocao, $ID_categoria]);

                $stmt = $pdo->query("SELECT LAST_INSERT_ID() AS ID");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_produto = $row['ID'];

                $stmt = $pdo->prepare("INSERT INTO empresa_produtos (ID_produto,ID_empresa) VALUES (?, ?)");
                $stmt->execute([$id_produto,$ID_empresa]);

            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Produto inserido com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/produtos/'), $diretorioAlternativo);
    }
}

if($_SESSION['ID_empresa'] == 0){
    $stmt = $pdo->prepare('
    SELECT p.ID,p.nome,p.img,p.descricao,p.preco_venda,p.preco_promocao,p.ativo,p.ID_categoria, ep.ID_empresa FROM '. $TABELA .' p
      JOIN empresa_produtos ep ON p.ID = ep.ID_produto');
} else {
    $stmt = $pdo->prepare('
    SELECT p.ID,p.nome,p.img,p.descricao,p.preco_venda,p.preco_promocao,p.ativo,p.ID_categoria, ep.ID_empresa FROM '. $TABELA .' p
      JOIN empresa_produtos ep ON p.ID = ep.ID_produto
     WHERE ep.ID_empresa = '.$_SESSION['ID_empresa'].'');
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

$dados = [
    'Nome',
    'Pre&ccedil;o',
    'Promo&ccedil;&atilde;o',
];
?>

<?php
function formatarPrecoParaTelaVenda($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '');
}
function formatarPrecoParaTelaPromocao($valor) {
    return number_format((float)$valor, 2, ',', '');
}

function gerarTbody($dados) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome;
        $preco_venda = formatarPrecoParaTelaVenda($view->preco_venda);
        $preco_promocao = formatarPrecoParaTelaPromocao($view->preco_promocao);
        $ativo = $view->ativo;
        
        $tbody .= "<tr>";
        $tbody .= "
        <td scope='row' class='text-start'>
            <a href='#formModal$id' data-bs-toggle='modal' class = 'text-dark' data-bs-target='#formModal$id'>$id - $nome <i class='lni lni-pencil'></i></a>
        </td>";
        $tbody .= "
        <td scope='row' id= 'row_preco_venda' class='text-center'>
            $preco_venda
        </td>";
        $tbody .= "
        <td scope='row' id= 'row_preco_promocao' class='text-center'>
            <label for='campo_$id' class='label-desativada'>R$</label>
            <input style='width: 15%;' id='campo_$id' data-id='$id' value='$preco_promocao' class='preco-promocao-input money'>
        </td>";
        $tbody .= "
        <td class='text-end' id='row_ativo'>
            <button type='button' class='btn' id='ativo_$id' value = '$ativo'>
                <i class='lni lni-checkmark-circle text-success'></i>
        ";
        $tbody .= "
        </button>
        <button type='button' class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#staticBackdrop$id'>
            <i class='lni lni-trash-can'></i>
        </button>";

        $tbody .= "</td></tr>";
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
        $descricao = $view->descricao;
        $imagemPath = $view->img;
        $ID_empresa = $view->ID_empresa;
        $ID_categoria = $view->ID_categoria;
        $preco_venda = formatarPrecoParaTelaPromocao($view->preco_venda);
        $preco_promocao = formatarPrecoParaTelaPromocao($view->preco_promocao);

        $modais .= gerarModalForm("formModal$id", "Alterar Produto", $nome, $descricao, $imagemPath, $preco_venda, $preco_promocao, $ID_empresa, $ID_categoria, $id);
        
        $modais .= gerarModalDelete($view); 
    }
    
    return $modais;
}
?>

<?php
function gerarModalForm($idModal, $titulo, $nome = '', $descricao = '', $imagemPath = '', $preco_venda = '', $preco_promocao = '', $ID_empresa = '', $ID_categoria = '', $id ='') {

    global $pdo;

    $empresas = new Empresa($pdo);
    $listaempresas = $empresas->searchEmpresas();

    $options = ['0' => 'Selecione'];
    foreach ($listaempresas as $empresa) {
        $options[$empresa->ID] = $empresa->nome;
    }

    $stmt = $pdo->prepare('SELECT c.ID,c.nome,c.img,c.ativo,ce.ID_empresa FROM categorias c
                             JOIN categorias_empresas ce ON ce.ID_categoria = c.ID
                            WHERE ativo = 1 AND ce.ID_empresa = ' . $_SESSION['ID_empresa']);
    $stmt->execute();
    $listacategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    $optionscategorias = ['0' => 'Selecione'];
    foreach ($listacategorias as $categoria) {
        $optionscategorias[$categoria['ID']] = $categoria['nome'];
    }

    $string = "
    <div class='modal fade' id='$idModal' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='{$idModal}Label' aria-hidden='true'>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='id' value='$id'>
            <div class='modal-dialog'>
                <div class='modal-content d-flex' style='width: 120%;'>
                    <div class='modal-header bg-secondary text-white text-center'>
                        <h1 class='modal-title fs-5 text-center' id='{$idModal}Label'>$titulo</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body row'>
                    <input type='hidden' name='id' id = 'idhidden' value='$id'>
                        "; 
                        if (isset($_SESSION['logged']) && $_SESSION['nivel'] >= 2 && $_SESSION['nivel'] < 4){
                        $string.= Form::InputText([
                            'size' => 12,
                            'name' => 'nome',
                            'label' => 'Nome',
                            'value' => $nome,
                            'required' => true,
                        ]);
                        }

                        if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 4){
                            $string.= Form::InputText([
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
                                'required' => true,
                            ]);
                        }
                        $string.= Form::InputFile([
                                    'size' => 12,
                                    'name' => 'img',
                                    'label' => 'Imagem',
                                    'id' => 'img',
                                    'attributes' => 'onchange="previewImage()"',
                                ]);
                        $string.= "
                                <div class='mb-3 text-start d-flex justify-content-center'>
                                    <img id='imgPreview' class = 'img-fluid' src='$imagemPath' alt='Preview da Imagem' style='display: " . ($imagemPath ? "block" : "none") . "; max-width: 100%; height: auto;'>
                                </div>
                                ";
                        $string.= Form::select([
                            'size' => 12,
                            'name' => 'ID_categoria',
                            'label' => 'Categoria',
                            'options' => $optionscategorias,
                            'value' => $ID_categoria,
                            'required' => true,
                        ]);
                        $string.= Form::textarea([
                                    'size' => 12,
                                    'name' => 'descricao',
                                    'label' => 'Descrição',
                                    'attributes' => 'style = "height:100%"',
                                    'value' => $descricao,
                                ]);
                        $string.= Form::inputMoney([
                                'size' => 6,
                                'name' => 'preco_venda',
                                'label' => 'Preço Venda',
                                'value' => $preco_venda,
                                'required' => true,
                        ]);
                        
                        $string.= Form::inputMoney([
                                'size' => 6,
                                'name' => 'preco_promocao',
                                'label' => 'Preço Promoção',
                                'value' => $preco_promocao,
                        ]); 
                        
                        $string.= "
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                            <button type='submit' class='btn btn-primary' id='btn_submit_produtos_$id' name='enviar'>Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>";


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