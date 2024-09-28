<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');

if (isset($_POST['id']) && isset($_POST['tipo'])) {
    $id = (int)$_POST['id'];
    $tipo = $_POST['tipo'];

    $tabela = 'pedidos'; 

    if ($tipo == "aceitar") {
        $stmt = $pdo->prepare("UPDATE $tabela SET status = 2 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'id' => $id, 'status' => 2]);  
    } elseif ($tipo == "finalizar") {
        $stmt = $pdo->prepare("UPDATE $tabela SET status = 3 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'id' => $id, 'status' => 3]);  
    } elseif ($tipo == "recusar") {
        $stmt = $pdo->prepare("UPDATE $tabela SET status = 4 WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'id' => $id, 'status' => 4]);  
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>
