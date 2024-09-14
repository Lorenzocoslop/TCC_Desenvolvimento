<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');
include_once '../../model/empresa.class.php';
include_once '../../model/utils.class.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$TABELA = 'usuarios';
$deletado = false;
$alterado = false;
$idAlterar = null;

function exibirMensagem($mensagem, $tipo = 'error') {
    echo "<div class='status-top-right text-center' id='status-container'><div class='status status-$tipo'><div class='status-message'>$mensagem</div></div></div>";
}

$diretorio = '../../gg/resources/uploads/usuarios/';

$diretorioAlternativo = realpath(__DIR__ . '/../../public_html/resources/uploads/usuarios/');

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
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($banner) {
        $imagemPath = $banner['img'];

        if (file_exists($imagemPath)) {
            unlink($imagemPath);
        }

        $deletado = true;

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/usuarios/'), $diretorioAlternativo);
    }
}

if (!empty($_POST)) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    } else {
        $senha = $_POST['senhaantiga'];
    }
    $telefone = str_replace(['(', ')','-', ' '], '', $_POST['telefone']);
    $ID_empresa = $_POST['ID_empresa'] ?? $_SESSION['ID_empresa'];
    $nivel = $_POST['nivel'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $numero = $_POST['numero'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = str_replace('-', '', $_POST['cep']);
    $cpf = str_replace(['.', '-'], '', $_POST['cpf']);
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

                $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, img = ?, email = ?, ID_empresa = ?, senha = ?, nivel = ?, telefone = ?, endereco = ?, bairro = ?, numero = ?, cidade = ?, estado = ?, cep = ?, cpf = ? WHERE id = ?");
                $stmt->execute([$nome, $baseUrl, $email, $ID_empresa, $senha, $nivel, $telefone, $endereco, $bairro, $numero, $cidade, $estado, $cep, $cpf, $id]);
                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Usu&aacute;rio atualizado com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, email = ?, ID_empresa = ?, senha = ?, nivel = ?, telefone = ?, endereco = ?, bairro = ?, numero = ?, cidade = ?, estado = ?, cep = ?, cpf = ? WHERE id = ?");
            $stmt->execute([$nome, $email, $ID_empresa, $senha, $nivel, $telefone, $endereco, $bairro, $numero, $cidade, $estado, $cep, $cpf, $id]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Usu&aacute;rio atualizado com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/usuarios/'), $diretorioAlternativo);
    } else {
        if ($imagem['error'] === 0) {

            $diretorio = '../resources/uploads/usuarios/';
            $baseUrl = $diretorio . uniqid() . '_' . basename($imagem['name']);
            
            if (move_uploaded_file($imagem['tmp_name'], $baseUrl)) {
                $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, img, email, ID_empresa, senha, nivel, telefone, endereco, bairro, numero, cidade, estado, cep, cpf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $baseUrl, $email, $ID_empresa, $senha, $nivel, $telefone, $endereco, $bairro, $numero, $cidade, $estado, $cep, $cpf]);
                echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Usu&aacute;rio inserido com sucesso </div></div></div>";
            } else {
                echo "Erro ao salvar o arquivo.";
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, email, ID_empresa, senha, nivel, telefone, endereco, bairro, numero, cidade, estado, cep, cpf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $ID_empresa, $senha, $nivel, $telefone, $endereco, $bairro, $numero, $cidade, $estado, $cep, $cpf]);
            echo "<div class='status-top-right text-center' id='status-container'><div class='status status-success'><div class='status-message'> Usu&aacute;rio inserido com sucesso </div></div></div>";
        }

        copiarDiretorio(realpath(__DIR__ . '/../resources/uploads/usuarios/'), $diretorioAlternativo);
    }
}
if($_SESSION['nivel'] == 4){
    $stmt = $pdo->prepare('SELECT * FROM '. $TABELA);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} else {
    $stmt = $pdo->prepare('SELECT * FROM '. $TABELA.' WHERE ID_empresa = '. $_SESSION['ID_empresa'].' AND nivel < 4 AND nivel > 1');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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

        $tbody .= "<tr>";
        $tbody .= "<td scope='row' class='text-start'>
            <a href='#formModal$id' class = 'text-dark' data-bs-toggle='modal' data-bs-target='#formModal$id'>$id - $nome <i class='lni lni-pencil'></i></a>
        </td>";
        $tbody .= "<td class='text-end' id='row_ativo'>";

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
        $email = $view->email;
        $nivel = $view->nivel;
        $ID_empresa = $view->ID_empresa;
        $telefone = $view->telefone;
        $endereco = $view->endereco;
        $bairro = $view->bairro;
        $numero = $view->numero;
        $cidade = $view->cidade;
        $estado = $view->estado;
        $cep = $view->cep;
        $cpf = $view->cpf;
        $senha = $view->senha;
        $imagemPath = $view->img;

        // Passa os dados corretamente para o modal
        $modais .= gerarModalForm("formModal$id", 
        "Alterar Usuário", 
        $nome,
        $email,
        $nivel, 
        $telefone, 
        $endereco,  
        $bairro, 
        $numero, 
        $cidade, 
        $estado, 
        $cep, 
        $cpf, 
        $imagemPath,
        $ID_empresa,
        $senha,
        $id );
        $modais .= gerarModalDelete($view); 
    }
    
    return $modais;
}
?>

<?php
function gerarModalForm($idModal, $titulo, $nome = '', $email = '', $nivel = '', $telefone = '', $endereco = '', $bairro = '', $numero = '', $cidade = '', $estado = '', $cep = '', $cpf = '', $imagemPath = '', $ID_empresa = '', $senha = '', $id= '') {
    global $pdo;

    $empresas = new Empresa($pdo);
    $listaempresas = $empresas->searchEmpresas();

    $options = ['0' => 'Selecione'];
    foreach ($listaempresas as $empresa) {
        $options[$empresa->ID] = $empresa->nome;
    }


    $string = "
    <div class='modal fade' id='$idModal' data-bs-backdrop='static'  data-bs-keyboard='false' tabindex='-1' aria-labelledby='{$idModal}Label' aria-hidden='true'>
        <form method='post' enctype='multipart/form-data' style='margin-right: 500px;'> 
        <input type='hidden' name='id' value='$id'>  
            <div class='modal-dialog'>
                <div class='modal-content' style='width:1000px; background-color: #F2F2F2;'>
                    <div class='modal-header bg-secondary text-white text-center'>
                        <h1 class='modal-title fs-5 w-100 text-center' id='{$idModal}Label'>$titulo</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                        
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

                            $string .= Form::InputFile([
                                'size' => 12,
                                'name' => 'img',
                                'label' => 'Foto de Perfil',
                                'id' => 'img',
                                'attributes' => 'onchange="previewImage()"',
                            ]); 
                            
                            $string .= "
                            <div class='mb-3 text-start d-flex justify-content-center' >
                                <img id='imgPreview' class = 'img-fluid'src='$imagemPath' alt='Preview da Imagem' style='width: 500px; height:500px; display: " . ($imagemPath ? "block" : "none") . "; max-width: 100%; height: auto;'>
                            </div>"; 

                            if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 4){
                                $string .= Form::select([
                                    'size' => 4,
                                    'name' => 'nivel',
                                    'label' => 'Permiss&otilde;es',
                                    'options' => [
                                        '2' => 'Ver',
                                        '3' => 'Ver e Editar',
                                        '4' => 'Administrador',
                                    ],
                                    'value' => $nivel,
                                ]);
                            }

                            if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 3){
                                $string .= Form::select([
                                    'size' => 4,
                                    'name' => 'nivel',
                                    'label' => 'Permiss&otilde;es',
                                    'options' => [
                                        '2' => 'Ver',
                                        '3' => 'Ver e Editar',
                                    ],
                                    'value' => $nivel,
                                ]);
                            }

                            $string.= Form::InputText([
                                'size' => 4,
                                'name' => 'email',
                                'label' => 'E-mail',
                                'value' => $email,
                                'required' => true,
                            ]);

                            $string.= Form::InputText([
                                'size' => 4,
                                'name' => 'telefone',
                                'label' => 'Telefone',
                                'class' => 'phone',
                                'value' => $telefone,
                            ]);

                            $string.= Form::InputText([
                                'size' => 4,
                                'name' => 'cpf',
                                'label' => 'CPF',
                                'value' => $cpf,
                                'class' => 'cpf'
                            ]);

                            $string.= Form::inputText([
                                'size' => 4,
                                'name' => 'senha',
                                'id' => 'senha',
                                'type' => 'password',
                                'label' => 'Senha',
                                'required' => true,
                            ]);

                            $string.= Form::inputHidden([
                                'name' => 'senhaantiga',
                                'value' => $senha,
                            ]);

                            $string.= Form::inputText([
                                'size' => 4,
                                'name' => 'confirm_senha',
                                'id' => 'confirm_senha',
                                'type' => 'password',
                                'label' => 'Confirmar Senha',
                                'required' => true,
                            ]);

                            $string .= '<div class="accordion" id="formAccordion">';
                        $string .= '<div class="accordion-item">';
                        $string .= '<h2 class="accordion-header" id="headingOne">';
                        $string .= '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">';
                        $string .= 'Endereço';
                        $string .= '</button>';
                        $string .= '</h2>';

                        $string .= '<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#formAccordion">';
                        $string .= '<div class="accordion-body">';
                        $string .= '<div class="row">';

                        $string.= Form::InputText([
                            'size' => 6,
                            'name' => 'endereco',
                            'label' => 'Rua',
                            'value' => $endereco,
                        ]);

                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'bairro',
                            'label' => 'Bairro',
                            'value' => $bairro,
                        ]);

                        $string.= Form::InputText([
                            'size' => 2,
                            'name' => 'numero',
                            'label' => 'N&uacute;mero',
                            'value' => $numero,
                        ]);

                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'cidade',
                            'label' => 'Cidade',
                            'value' => $cidade,
                        ]);

                        $estados = [];
                        $estados = Utils::listarEstados();

                        $string.= Form::select([
                            'size' => 4,
                            'name' => 'estado',
                            'options' => $estados,
                            'label' => 'Estado',
                            'value' => $estado,
                        ]);

                        $string.= Form::InputText([
                            'size' => 4,
                            'name' => 'cep',
                            'id' => 'cep',
                            'label' => 'CEP',
                            'class' => 'cep',
                            'value' => $cep,
                        ]);

                        $string .= '</div>'; // Fechando a div .row
                        $string .= '</div>'; // Fechando a div .accordion-body
                        $string .= '</div>'; // Fechando a div #collapseOne
                        $string .= '</div>'; // Fechando a div .accordion-item
                        $string .= '</div>'; // Fechando a div .accordion

                        $string .= "
                        </div>
                            </div>
                            <div class='modal-footer' style='background-color: white'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                <button type='submit' class='btn btn-primary' id='btn_submit_usuarios_$id' name='enviar'>Salvar</button>
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
