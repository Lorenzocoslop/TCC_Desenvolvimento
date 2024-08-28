<?php
require '../connection/config.php';

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
$postfilter = $post ? array_map('strip_tags', $post) : []; // Verifica se $post é um array antes de usar array_map

$message = null;
$counter = 0;

if ($counter == TIMESBLOCKED) {
    unset($_SESSION['nome']);
    unset($_SESSION['nivel']);
    unset($_SESSION['ID_empresa']);
    unset($_SESSION['email']);
    unset($_SESSION['ID']);
    unset($_SESSION['logged']);
    header('Location: ../../view/v-login.php'); // Correção: 'Location' ao invés de 'locate'
    exit;
}

if (BLOCKED == 1 && $counter == TIMESBLOCKED || isset($_SESSION['blocked']) && $_SESSION['blocked'] == 1) {
    unset($_SESSION['nome']);
    unset($_SESSION['nivel']);
    unset($_SESSION['ID_empresa']);
    unset($_SESSION['email']);
    unset($_SESSION['ID']);
    unset($_SESSION['logged']);

    $_SESSION['blocked'] = 1;
    setcookie("LBlocked", 1, time() + 86400, '/'); // Correção: time() para definir a data de expiração do cookie

    header('Location: ../../view/v-login.php'); // Correção: 'Location' ao invés de 'locate'
    exit;
}

$email = $_COOKIE['LE'] ?? null; // Correção: uso de null coalesce operator para evitar undefined index
if (!$email || empty($email) || $email === null || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    session_destroy();
    header('Location: ../../view/v-login.php'); // Correção: 'Location' ao invés de 'locate'
    exit;
}

$read = $pdo->prepare("SELECT ID, nome, email, senha, nivel, ID_empresa FROM usuarios WHERE email = :email");
$read->bindValue(':email', $email); // Correção: bindValue ao invés de blindvalue
$read->execute();

$lines = $read->rowCount();

if ($lines == 0) {
    $_SESSION['counter'] = $counter + 1;
    if ($counter == TIMESBLOCKED) {
        session_destroy();
        header('Location: ../../view/v-login.php');
        exit;
    } else {
        session_destroy();
        header('Location: ../../view/v-login.php');
        exit;
    }
} 

$show = $read->fetch(PDO::FETCH_ASSOC); // Correção: fetch para obter a linha do banco de dados

$verificasenha = password_verify($_COOKIE['LP'], $show['senha']);

if ($verificasenha) {
    $_SESSION['ID'] = $show['ID'];
    $_SESSION['nome'] = $show['nome'];
    $_SESSION['email'] = $show['email'];
    $_SESSION['senha'] = $show['senha'];
    $_SESSION['nivel'] = $show['nivel'];
    $_SESSION['ID_empresa'] = $show['ID_empresa'];
    $_SESSION['logged'] = 1;
    unset($_SESSION['counter']);

    header('Location: ../../view/v-home.php'); // Correção: 'Location' ao invés de 'locate'
    exit;
} else {
    session_destroy();
    header('Location: ../../view/v-login.php'); // Correção: 'Location' ao invés de 'locate'
    exit;
}


?>