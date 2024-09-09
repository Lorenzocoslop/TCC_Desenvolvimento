<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../../connection/connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tipo']) && $_POST['tipo'] === 'atualizar_notificacoes') {
        $pdo = conectar();

        if (isset($_SESSION['ID'])) {
            $stmt = $pdo->prepare('
                SELECT COUNT(cp.ID_produto) AS qtd 
                FROM carrinho c
                JOIN carrinho_produtos cp ON c.ID = cp.ID_carrinho
                JOIN usuarios u ON u.ID = c.ID_usuario
                JOIN produtos p ON cp.ID_produto = p.ID
                WHERE c.ID_usuario = :id_usuario');
            $stmt->execute([':id_usuario' => $_SESSION['ID']]);
            
            $notificacoes = $stmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'notificacoes' => [
                    'notificacoes' => $notificacoes
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo de ação inválido']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>
