<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../../connection/connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tipo']) && $_POST['tipo'] === 'atualizar_totais') {
        $pdo = conectar();

        // Calcular totais
        $stmt = $pdo->prepare('
            SELECT 
                cp.ID_produto, 
                p.preco_venda, 
                p.preco_promocao, 
                cp.qtd, 
                cu.valor_desc 
            FROM 
                carrinho_produtos cp
                JOIN produtos p ON cp.ID_produto = p.ID
                LEFT JOIN cupons cu ON cu.ID = (SELECT ID_cupom FROM carrinho WHERE ID_usuario = :id_usuario)
            WHERE 
                cp.ID_carrinho = (SELECT ID FROM carrinho WHERE ID_usuario = :id_usuario)
        ');

        $stmt->execute([':id_usuario' => $_SESSION['ID']]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalsemcupom = 0;
        $totalcomcupom = 0;
        $valor_desc = 0;

        foreach ($dados as $view) {
            $qtd = $view['qtd'];
            $preco_venda = $view['preco_venda'];
            $preco_promocao = $view['preco_promocao'];
            $valor_desc = $view['valor_desc'];

            if ($preco_promocao > 0) {
                $totalsemcupom += $preco_promocao * $qtd;
            } else {
                $totalsemcupom += $preco_venda * $qtd;
            }
        }

        $totalcomcupom = $totalsemcupom - ($totalsemcupom * $valor_desc / 100);

        $formatarDinheiro = function($valor) {
            return 'R$ ' . number_format($valor, 2, ',', '.');
        };

        $totalsemcupomFormatado = $formatarDinheiro($totalsemcupom);
        $totalcomcupomFormatado = $formatarDinheiro($totalcomcupom);

        echo json_encode([
            'success' => true,
            'totais' => [
                'totalsemcupom' => '<strong>'.$totalsemcupomFormatado.'</strong>',
                'totalcomcupom' => '<strong>'.$totalcomcupomFormatado.'</strong>'
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>