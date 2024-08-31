<?php
include_once realpath(__DIR__ . '/../connection/connection.php');


$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = (int)$data['id'];

    // Preparar a consulta para atualizar o status no banco de dados
    $stmt = $pdo->prepare("UPDATE cupons SET ativo = 0 WHERE id = ?");
    $success = $stmt->execute([$id]);

    // Retornar a resposta para o JavaScript
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
?>