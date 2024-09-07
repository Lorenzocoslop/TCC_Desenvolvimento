<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

$TABELA = 'categorias';
$deletado = false;
$alterado = false;
$idAlterar = null;

function exibirMensagem($mensagem, $tipo = 'error') {
    echo "<div class='status-top-right text-center' id='status-container'><div class='status status-$tipo'><div class='status-message'>$mensagem</div></div></div>";
}

$diretorio = '../../gg/resources/uploads/categorias/';

$diretorioAlternativo = realpath(__DIR__ . '/../../public_html/resources/uploads/categorias/');

function copiarDiretorio($src, $dest) {
    if (!file_exists($dest)) {
        mkdir($dest, 0755, true);
    }

    $dir = opendir($src);

    $filesInDest = [];
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
    $files = array_diff(scandir($dir), ['.', '..']);
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
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($categoria) {
        $imagemPath = $categoria['img'];

        if (file_exists($imagemPath)) {
            unlink($imagemPath);
        }

        $deletado = true;

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/categorias/'), $diretorioAlternativo);
    }
}

if (!empty($_POST)) {
    $nome = $_POST['nome'];
    $imagem = $_FILES['img'];
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        $stmt = $pdo->prepare("SELECT img FROM $TABELA WHERE id = ?");
        $stmt->execute([$id]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagemAntiga = $registro['img'];
        
        if ($imagem['error'] === 0) {

            $baseUrl = $diretorio . uniqid() . '_' . basename($imagem['name']);
            
            if (move_uploaded_file($imagem['tmp_name'], $baseUrl)) {
                if ($imagemAntiga && file_exists($imagemAntiga)) {
                    unlink($imagemAntiga);
                }

                $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, img = ? WHERE id = ?");
                $stmt->execute([$nome, $baseUrl, $id]);
                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Categoria atualizada com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ? WHERE id = ?");
            $stmt->execute([$nome, $id]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Categoria atualizada com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/categorias/'), $diretorioAlternativo);
    } else {
        if ($imagem['error'] === 0) {

            $diretorio = '../resources/uploads/categorias/';
            $baseUrl = $diretorio . uniqid() . '_' . basename($imagem['name']);
            
            if (move_uploaded_file($imagem['tmp_name'], $baseUrl)) {
                $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, img) VALUES (?, ?)");
                $stmt->execute([$nome, $baseUrl]);
                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Categoria inserida com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO $TABELA (nome) VALUES (?)");
            $stmt->execute([$nome, $descricao]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Categoria inserida com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/categorias/'), $diretorioAlternativo);
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
        $thead .= "<th scope='col' class='text-start'>{$dados}</th>";
    }

    $thead .= "</tr></thead>";

    return $thead;
}

$dados = 
[
    'Nome',
];
?>

<?php
function gerarTbody($dados) {
    $tbody = "<tbody>";
    
    foreach ($dados as $view) {
        $id = $view->ID;
        $nome = $view->nome; 
        $ativo = $view->ativo;

        $tbody .= "<tr>";
        $tbody .= "<td scope='row' class='text-start'>
            <a href='#formModal$id' class = 'text-dark' data-bs-toggle='modal' data-bs-target='#formModal$id'>$id - $nome <i class='lni lni-pencil'></i></a>
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
        $imagemPath = $view->img;

        $modais .= gerarModalForm("formModal$id", "Alterar Categoria", $nome, $imagemPath, $id);
        $modais .= gerarModalDelete($view); 
    }
    
    return $modais;
}
?>

<?php
function gerarModalForm($idModal, $titulo, $nome = '', $imagemPath = '', $id= '') {

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

                        $string .= Form::InputFile([
                            'size' => 12,
                            'name' => 'img',
                            'label' => 'Imagem',
                            'id' => 'img',
                            'attributes' => 'onchange="previewImage()"',
                        ]); 
                        
                        $string .= "
                        <div class='mb-3 text-start d-flex justify-content-center'>
                            <img id='imgPreview' class = 'img-fluid'src='$imagemPath' alt='Preview da Imagem' style='display: " . ($imagemPath ? "block" : "none") . "; max-width: 100%; height: auto;'>
                        </div>";

                        $string .=
                         "
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                    <button type='submit' class='btn btn-primary' id='btn_submit_categorias_$id' name='enviar'>Salvar</button>
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
