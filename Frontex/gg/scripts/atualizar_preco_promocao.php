<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');


if (isset($_POST['id']) && isset($_POST['preco_promocao'])) {
    $id = (int)$_POST['id'];
    $preco_promocao = number_format((float)str_replace(',', '.', $_POST['preco_promocao']), 2, '.', '');

    $stmt = $pdo->prepare("UPDATE produtos SET preco_promocao = ? WHERE id = ?");
    if ($stmt->execute([$preco_promocao, $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>