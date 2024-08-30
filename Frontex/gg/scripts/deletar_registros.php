<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once realpath(__DIR__ . '/../connection/connection.php');

if (isset($_POST['id']) && isset($_POST['tabela'])) {
    $id = (int)$_POST['id'];
    $tabela = $_POST['tabela'];
    
    if (isset($_POST['img'])) {
        // Prepara e executa a consulta para obter a imagem
        $stmt = $pdo->prepare("SELECT img FROM $tabela WHERE id = ?");
        $stmt->execute([$id]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $imagemPath = $registro['img'];

            // Deleta a imagem do sistema de arquivos, se existir
            if (file_exists($imagemPath) && $imagemPath !== '../../image/produto-sem-imagem.jpg') {
                unlink($imagemPath);
            }
        }
    }

    try {



        $stmt = $pdo->prepare("DELETE FROM $tabela WHERE id = ?");
        $stmt->execute([$id]);
        

        // Obtém o ID da empresa_produtos associado ao ID do produto
        $stmt = $pdo->prepare("SELECT ID FROM empresa_produtos WHERE ID_produto = ? AND ID_empresa = ?");
        $stmt->execute([$id, $_SESSION['ID_empresa']]);
        $registroEmpresaProduto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registroEmpresaProduto) {
            $idEp = $registroEmpresaProduto['ID'];

            // Deleta o registro da tabela empresa_produtos
            $stmt = $pdo->prepare("DELETE FROM empresa_produtos WHERE ID = ?");
            $stmt->execute([$idEp]);
        }

        $_SESSION['status_message'] = 'Registro deletado com sucesso';
        $_SESSION['status_class'] = 'success';
        echo json_encode(['status' => 'success', 'redirect' => '']);
    } catch (PDOException $e) {
        $_SESSION['status_message'] = 'Erro ao deletar o registro: ' . $e->getMessage();
        $_SESSION['status_class'] = 'error';
        echo json_encode(['status' => 'error']);
    }
} else {
    $_SESSION['status_message'] = 'Dados inválidos';
    $_SESSION['status_class'] = 'error';
    echo json_encode(['status' => 'error']);
}
?>
