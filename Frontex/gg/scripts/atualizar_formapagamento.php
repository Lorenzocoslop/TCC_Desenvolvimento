<?php
session_start();
include_once realpath(__DIR__ . '/../../connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_formapagamento = $_POST['id_formapagamento'];
    $id_empresa = $_POST['id_empresa'];
    $action = $_POST['action'];

    if ($action === 'inserir') {
        $stmt = $pdo->prepare('INSERT INTO formaspagamento_empresas (ID_empresa, ID_formapagamento) VALUES (?, ?)');
        if ($stmt->execute([$id_empresa, $id_formapagamento])) {
            echo json_encode(['status' => 'success', 'message' => 'Forma de pagamento adicionada com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar forma de pagamento']);
        }
    } elseif ($action === 'remover') {
        $stmt = $pdo->prepare('DELETE FROM formaspagamento_empresas WHERE ID_empresa = ? AND ID_formapagamento = ?');
        if ($stmt->execute([$id_empresa, $id_formapagamento])) {
            echo json_encode(['status' => 'success', 'message' => 'Forma de pagamento removida com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao remover forma de pagamento']);
        }
    }
}
?>