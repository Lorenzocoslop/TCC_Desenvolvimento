<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');

include_once realpath(__DIR__ . '/../../connection/connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produtos']) && isset($_POST['usuario']) && isset($_POST['formapagamento'])) {
        $produtos = $_POST['produtos'];
        $usuario = $_POST['usuario'];
        $endereco = $_POST['endereco'];
        $bairro = $_POST['bairro'];
        $numero = $_POST['numero'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $cep = $_POST['cep'];
        $complemento = $_POST['complemento'];
        $qtd = $_POST['qtd'];
        $formapagamento = $_POST['formapagamento'];
        $carrinho = $_POST['carrinho'];
        $pdo = conectar();

        $stmt = $pdo->prepare('INSERT INTO pedidos(nome,ID_empresa,endereco,bairro,numero,cidade,estado,cep,complemento,ID_formapagamento,ID_usuario) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $result = $stmt->execute([
            $usuario['nome'],
            $_SESSION['ID_empresa'],
            $endereco,
            $bairro,
            $numero,
            $cidade,
            $estado,
            $cep,
            $complemento,
            $formapagamento,
            $usuario['ID'],
        ]);

        $idPedido = $pdo->lastInsertId();

        $i=0; foreach($produtos as $produto){
            $stmt = $pdo->prepare('INSERT INTO pedidos_produtos(ID_produto,ID_pedido,qtd) VALUES(?, ?, ?)');
            $result = $stmt->execute([
                $produto,
                $idPedido,
                $qtd[$i]
            ]);
            $i++;
        }
        


        $stmt = $pdo->prepare('DELETE FROM carrinho_produtos WHERE ID_carrinho = :id_carrinho');
        $result = $stmt->execute([
            ':id_carrinho' => $carrinho,
        ]);
        if($formapagamento == 2){
            echo json_encode(['success' => $result, 'redirect' => '../view/v-pagamento-pix.php']);
        } else {
            echo json_encode(['success' => $result, 'redirect' => '../view/v-home.php']);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}