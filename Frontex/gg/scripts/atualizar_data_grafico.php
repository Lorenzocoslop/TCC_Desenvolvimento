<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$periodo = $_GET['periodo'];

include_once realpath(__DIR__ . '/../../connection/connection.php');

$pdo->query("SET lc_time_names = 'pt_BR'");

if ($periodo === 'personalizado') {
    $data_inicio = $_GET['data_inicio'];
    $data_fim = $_GET['data_fim'];

    $data_fim = date('Y-m-d H:i:s', strtotime($data_fim . ' 23:59:59'));

    $sql = "SELECT DATE_FORMAT(data_pedido, '%d/%m') AS dia, COUNT(*) AS quantidade
            FROM pedidos
            WHERE data_pedido >= :data_inicio AND data_pedido <= :data_fim
            GROUP BY dia
            ORDER BY data_pedido";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['data_inicio' => $data_inicio, 'data_fim' => $data_fim]);

    $sql_total = "SELECT COUNT(*) AS total_pedidos,
                         DATEDIFF(:data_fim, :data_inicio) + 1 AS dias_intervalo,
                         COUNT(*) / (DATEDIFF(:data_fim, :data_inicio) + 1) AS media_pedidos, 0 AS total_recebido
                  FROM pedidos
                  WHERE data_pedido BETWEEN :data_inicio AND :data_fim";
    $stmt_total = $pdo->prepare($sql_total);
    $stmt_total->execute(['data_inicio' => $data_inicio, 'data_fim' => $data_fim]);

    // Consulta para obter total recebido
    $sql_recebido = "SELECT p.ID AS ID_produto, pe.ID AS ID_pedido, pp.qtd, 
                        CASE 
                            WHEN p.preco_promocao > 0 THEN p.preco_promocao
                            ELSE p.preco_venda
                        END AS total
                    FROM produtos p
                    JOIN pedidos_produtos pp ON p.ID = pp.ID_produto
                    JOIN pedidos pe ON pe.ID = pp.ID_pedido
                    WHERE pe.data_pedido >= :data_inicio AND pe.data_pedido <= :data_fim
                    AND pe.ID_empresa = {$_SESSION['ID_empresa']}";
    $stmt_recebido = $pdo->prepare($sql_recebido);
    $stmt_recebido->execute(['data_inicio' => $data_inicio, 'data_fim' => $data_fim]);
    
    $recebido_result = $stmt_recebido->fetchAll(PDO::FETCH_ASSOC);
    $total_recebido = 0;
    if ($recebido_result) {
        foreach($recebido_result as $recebido) {
            $total_recebido += ($recebido['total'] * $recebido['qtd']);
        }
    }

} else {
    switch ($periodo) {
        case 'semana':
            $sql = "SELECT DATE_FORMAT(data_pedido, '%W') AS dia, COUNT(*) AS quantidade
                    FROM pedidos
                    WHERE data_pedido >= NOW() - INTERVAL 1 WEEK
                    GROUP BY dia
                    ORDER BY data_pedido";
            $sql_total = "SELECT COUNT(*) AS total_pedidos, COUNT(*) / 7 AS media_pedidos, 0 AS total_recebido
                          FROM pedidos
                          WHERE data_pedido >= NOW() - INTERVAL 1 WEEK";

            $sql_recebido = "SELECT p.ID AS ID_produto, pe.ID AS ID_pedido, pp.qtd, 
                        CASE 
                            WHEN p.preco_promocao > 0 THEN p.preco_promocao
                            ELSE p.preco_venda
                        END AS total
                    FROM produtos p
                    JOIN pedidos_produtos pp ON p.ID = pp.ID_produto
                    JOIN pedidos pe ON pe.ID = pp.ID_pedido
                    WHERE pe.data_pedido >= NOW() - INTERVAL 1 WEEK 
                    AND pe.ID_empresa = {$_SESSION['ID_empresa']}";
            $stmt_recebido = $pdo->prepare($sql_recebido);
            $stmt_recebido->execute();
            
            $recebido_result = $stmt_recebido->fetchAll(PDO::FETCH_ASSOC);
            $total_recebido = 0;
            if ($recebido_result) {
                foreach($recebido_result as $recebido) {
                    $total_recebido += ($recebido['total'] * $recebido['qtd']);
                }
            }
            break;
        case 'mes':
            $sql = "SELECT DATE_FORMAT(data_pedido, '%d/%m') AS dia, COUNT(*) AS quantidade
                    FROM pedidos
                    WHERE data_pedido >= NOW() - INTERVAL 1 MONTH
                    GROUP BY dia
                    ORDER BY data_pedido";
            $sql_total = "SELECT COUNT(*) AS total_pedidos, COUNT(*) / 30 AS media_pedidos, 0 AS total_recebido
                          FROM pedidos
                          WHERE data_pedido >= NOW() - INTERVAL 1 MONTH";
            $sql_recebido = "SELECT p.ID AS ID_produto, pe.ID AS ID_pedido, pp.qtd, 
                        CASE 
                            WHEN p.preco_promocao > 0 THEN p.preco_promocao
                            ELSE p.preco_venda
                        END AS total
                    FROM produtos p
                    JOIN pedidos_produtos pp ON p.ID = pp.ID_produto
                    JOIN pedidos pe ON pe.ID = pp.ID_pedido
                    WHERE pe.data_pedido >= NOW() - INTERVAL 1 MONTH 
                    AND pe.ID_empresa = {$_SESSION['ID_empresa']}";
            $stmt_recebido = $pdo->prepare($sql_recebido);
            $stmt_recebido->execute();

            $recebido_result = $stmt_recebido->fetchAll(PDO::FETCH_ASSOC);
            $total_recebido = 0;
            if ($recebido_result) {
                foreach($recebido_result as $recebido) {
                    $total_recebido += ($recebido['total'] * $recebido['qtd']);
                }
            }
            break;
        case 'ano':
            $sql = "SELECT DATE_FORMAT(data_pedido, '%m/%Y') AS mes, COUNT(*) AS quantidade
                    FROM pedidos
                    WHERE data_pedido >= NOW() - INTERVAL 1 YEAR
                    GROUP BY mes
                    ORDER BY data_pedido";
            $sql_total = "SELECT COUNT(*) AS total_pedidos, COUNT(*) / 365 AS media_pedidos, 0 AS total_recebido
                          FROM pedidos
                          WHERE data_pedido >= NOW() - INTERVAL 1 YEAR";
            $sql_recebido = "SELECT p.ID AS ID_produto, pe.ID AS ID_pedido, pp.qtd, 
                        CASE 
                            WHEN p.preco_promocao > 0 THEN p.preco_promocao
                            ELSE p.preco_venda
                        END AS total
                    FROM produtos p
                    JOIN pedidos_produtos pp ON p.ID = pp.ID_produto
                    JOIN pedidos pe ON pe.ID = pp.ID_pedido
                    WHERE pe.data_pedido >= NOW() - INTERVAL 1 YEAR 
                    AND pe.ID_empresa = {$_SESSION['ID_empresa']}";
            $stmt_recebido = $pdo->prepare($sql_recebido);
            $stmt_recebido->execute();

            $recebido_result = $stmt_recebido->fetchAll(PDO::FETCH_ASSOC);
            $total_recebido = 0;
            if ($recebido_result) {
                foreach($recebido_result as $recebido) {
                    $total_recebido += ($recebido['total'] * $recebido['qtd']);
                }
            }
            break;
    }

    $stmt = $pdo->query($sql);
    $stmt_total = $pdo->query($sql_total);
}

// Obter dados para o gráfico de pizza
$sql_status = "SELECT status, COUNT(*) AS quantidade
               FROM pedidos
               WHERE ID_empresa = :id_empresa"; // Permitindo adição dinâmica de condições

if ($periodo === 'personalizado') {
    $sql_status .= " AND data_pedido BETWEEN :data_inicio AND :data_fim";
} else {
    switch ($periodo) {
        case 'semana':
            $sql_status .= " AND data_pedido >= NOW() - INTERVAL 1 WEEK";
            break;
        case 'mes':
            $sql_status .= " AND data_pedido >= NOW() - INTERVAL 1 MONTH";
            break;
        case 'ano':
            $sql_status .= " AND data_pedido >= NOW() - INTERVAL 1 YEAR";
            break;
    }
}

$sql_status .= " GROUP BY status";

$stmt_status = $pdo->prepare($sql_status);

if ($periodo === 'personalizado') {
    $stmt_status->bindParam(':data_inicio', $data_inicio);
    $stmt_status->bindParam(':data_fim', $data_fim);
}

$stmt_status->bindParam(':id_empresa', $_SESSION['ID_empresa']);
$stmt_status->execute();
$status_result = $stmt_status->fetchAll(PDO::FETCH_ASSOC);

$status_labels = [];
$status_quantities = [];
foreach ($status_result as $row) {
    switch ($row['status']) {
        case '1':
            $status_labels[] = 'Aberto';
            break;
        case '2':
            $status_labels[] = 'Em andamento';
            break;
        case '3':
            $status_labels[] = 'Fechado';
            break;
        case '4':
            $status_labels[] = 'Cancelado';
            break;
        default:
            $status_labels[] = 'Desconhecido';
            break;
    }
    $status_quantities[] = $row['quantidade'];
}

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_result = $stmt_total->fetch(PDO::FETCH_ASSOC);

$quantidade = [];
$categorias = [];

foreach ($resultado as $row) {
    $categorias[] = ucfirst($row['dia'] ?? $row['mes']);
    $quantidade[] = $row['quantidade'];
}

// Enviando os dados em formato JSON
echo json_encode([
    'quantidade' => $quantidade,
    'categorias' => $categorias,
    'total_pedidos' => $total_result['total_pedidos'],
    'media_pedidos' => number_format($total_result['media_pedidos'], 2, ',', '.'),
    'total_recebido' => number_format($total_recebido, 2, ',', '.'),
    'status_labels' => $status_labels,
    'status_quantities' => $status_quantities,
]);
?>
