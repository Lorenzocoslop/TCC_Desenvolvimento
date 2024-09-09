<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../../connection/connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ID_produto'])) {
        $idProduto = $_POST['ID_produto'];
        
        $pdo = conectar();

        // Verificar se o produto já está no carrinho
        $stmt = $pdo->prepare('SELECT COUNT(ID) FROM carrinho_produtos WHERE ID_produto = :id_produto AND ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)');
        $stmt->execute([
            ':id_produto' => $idProduto,
            ':id_usuario' => $_SESSION['ID']
        ]);
        $count = $stmt->fetchColumn();

        // Verificar se o usuário já tem um carrinho
        $stmt = $pdo->prepare('SELECT COUNT(ID) FROM carrinho WHERE ID_usuario = :id_usuario');
        $stmt->execute([
            ':id_usuario' => $_SESSION['ID']
        ]);
        $checaCarrinho = $stmt->fetchColumn();

        if ($checaCarrinho > 0) {
            if ($count > 0) {
                // Atualiza a quantidade de produtos no carrinho
                $stmt = $pdo->prepare('UPDATE carrinho_produtos SET qtd = qtd + 1 WHERE ID_produto = :id_produto AND ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)');
                $result = $stmt->execute([
                    ':id_produto' => $idProduto,
                    ':id_usuario' => $_SESSION['ID']
                ]);
            } else {
                // Adiciona um novo produto ao carrinho existente
                $stmt = $pdo->prepare('INSERT INTO carrinho_produtos (ID_carrinho, ID_produto) VALUES ((SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario), :id_produto)');
                $result = $stmt->execute([
                    ':id_produto' => $idProduto,
                    ':id_usuario' => $_SESSION['ID']
                ]);
            }
        } else {
            // Cria um novo carrinho para o usuário
            $stmt = $pdo->prepare('INSERT INTO carrinho (ID_usuario, ID_empresa) VALUES (?, ?)');
            $result = $stmt->execute([
                $_SESSION['ID'],
                $_SESSION['ID_empresa']
            ]);

            // Adiciona o produto ao novo carrinho
            if ($result) {
                if ($count > 0) {
                    $stmt = $pdo->prepare('UPDATE carrinho_produtos SET qtd = qtd + 1 WHERE ID_produto = :id_produto AND ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)');
                    $result = $stmt->execute([
                        ':id_produto' => $idProduto,
                        ':id_usuario' => $_SESSION['ID']
                    ]);
                } else {
                    $stmt = $pdo->prepare('INSERT INTO carrinho_produtos (ID_carrinho, ID_produto) VALUES ((SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario), :id_produto)');
                    $result = $stmt->execute([
                        ':id_produto' => $idProduto,
                        ':id_usuario' => $_SESSION['ID']
                    ]);
                }
            }
        }

        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
