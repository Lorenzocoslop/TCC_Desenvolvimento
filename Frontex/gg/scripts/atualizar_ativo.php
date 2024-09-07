<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');


if (isset($_POST['id']) && isset($_POST['ativo']) && isset($_POST['tabela'])) {
    $id = (int)$_POST['id'];
    $ativo = (int)$_POST['ativo'];
    $tabela = $_POST['tabela'];


    // Prepare e execute a consulta SQL com o nome da tabela modificado
    $stmt = $pdo->prepare("UPDATE $tabela SET ativo = ? WHERE id = ?");
    if ($stmt->execute([$ativo, $id])) {
    echo json_encode(['success' => true]);
    } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>