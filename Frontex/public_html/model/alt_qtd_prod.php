<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

include_once realpath(__DIR__ . '/../../connection/connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ID_produto'])) {

        if($_POST['tipo'] == 'reduzir'){
            $idProduto = $_POST['ID_produto'];
            $pdo = conectar();
            $stmt = $pdo->prepare('UPDATE carrinho_produtos SET qtd = qtd-1 WHERE ID_produto = :id_produto AND ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)');
            if($_POST['qtd'] > 1) {
                $result = $stmt->execute([
                    ':id_produto' => $idProduto,
                    ':id_usuario' => $_SESSION['ID']
                ]); 
            } else{
                $result='';
            }
        }else if($_POST['tipo'] == 'aumentar'){
            $idProduto = $_POST['ID_produto'];

            $pdo = conectar();
            $stmt = $pdo->prepare('UPDATE carrinho_produtos SET qtd = qtd+1 WHERE ID_produto = :id_produto AND ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)');
                $result = $stmt->execute([
                    ':id_produto' => $idProduto,
                    ':id_usuario' => $_SESSION['ID']
                ]);   
        }
        

        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}