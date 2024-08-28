<?php

include_once realpath(__DIR__ . '/../connection/connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados enviados via POST
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    
    // Verifica se os campos são preenchidos
    if (empty($nome) || empty($email) || empty($senha)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    try {
        // Prepara e executa a consulta usando a conexão $pdo já existente
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, ID_empresa) VALUES (:nome, :email, :senha, :ID_empresa)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':ID_empresa', $_SESSION['ID_empresa']);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Cadastro realizado com sucesso.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar: ' . $e->getMessage()]);
    }
}
?>
