<?php
include_once realpath(__DIR__ . '/../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

if ($_POST['tabela'] == 'banners'){

    $TABELA = 'banners';
    $response = ['success' => false, 'message' => '<div class="status-top-right text-center" id="status-container"><div class="status status-error"><div class="status-message"> Erro </div></div></div>'];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'] ?? null;
        $id = $_POST['id'] ?? null;
        $imagem = $_FILES['img'] ?? null;
    
        // Diretório onde as imagens serão salvas
        $diretorio = '../resources/uploads/banners/';
    
        // Processamento da imagem
        if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
            $imageName = uniqid() . '_' . basename($imagem['name']);
            $imagePath = $diretorio . $imageName;
    
            if (move_uploaded_file($imagem['tmp_name'], $imagePath)) {
                // Verifica se é uma atualização e se a imagem anterior deve ser removida
                if ($id) {
                    $stmt = $pdo->prepare("SELECT img FROM $TABELA WHERE id = ?");
                    $stmt->execute([$id]);
                    $oldImage = $imagePath;
    
                    if ($oldImage && file_exists($diretorio . $oldImage)) {
                        unlink($diretorio . $oldImage); // Remove a imagem anterior
                    }
                }
            } else {
                $response = ['success' => false, 'message' => '<div class="status-top-right text-center" id="status-container"><div class="status status-error"><div class="status-message"> Erro ao enviar imagem </div></div></div>'];
                echo json_encode($response);
                exit;
            }
        }
    
        // Atualização
        if ($id) {
            if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
                $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, descricao = ?, img = ? WHERE id = ?");
                $stmt->execute([$nome, $descricao, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE $TABELA SET nome = ?, descricao = ? WHERE id = ?");
                $stmt->execute([$nome, $descricao, $id]);
            }
            $response = ['success' => true, 'message' => '<div class="status-top-right text-center" id="status-container"><div class="status status-error"><div class="status-message"> Banner atualizado com sucesso </div></div></div>'];
        } else {
            // Inserção
            $stmt = $pdo->prepare("INSERT INTO $TABELA (nome, descricao, img) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $descricao, $imagePath ?? null]);
            $response = ['success' => true, 'message' => '<div class="status-top-right text-center" id="status-container"><div class="status status-success"><div class="status-message"> Banner inserido com sucesso </div></div></div>'];
        }
    
        echo json_encode($response['message']);
        exit;
    }
}

