<?php
require '../connection/config.php';

$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_SPECIAL_CHARS);
$postfilter = array_map('strip_tags', $post);


$message = null;

$counter = 0;


$email = $postfilter['login_email'];
$senha = $postfilter['login_password'];
$csenha = $postfilter['confirm_password'];
if (!$email || empty($email) || $email = NULL || filter_var($email,FILTER_VALIDATE_EMAIL)) {
    $message = ['status' => 'info', 'message' => 'Seu email não é válido', 'redirect' => ''];
    echo json_encode($message);
    return;
}

$read = $pdo->prepare("SELECT ID, email, senha FROM usuarios WHERE email = :email");
$read -> blindvalue(':email', $email);
$read -> execute();

$lines = $read -> rowcount();

if ($lines == 0) {
        $message = ['status' => 'warning', 'message' => 'Seu email é inválido', 'redirect' => ''];
        echo json_encode($message);
        return;
} 

if($senha != $csenha){
    $message = ['status' => 'info', 'message' => 'As senhas não são iguais', 'redirect' => ''];
        echo json_encode($message);
        return;
}

$novasenha = password_hash($senha, PASSWORD_DEFAULT);

$update = $pdo->prepare("UPDATE usuarios SET senha = :senha WHERE email = :email");
$update -> bindValue(':senha', $novasenha);
$update -> bindValue(':email', $email);
$update -> execute();

if($update){
    $message = ['status' => 'success', 'message' => 'Senha Alterada', 'redirect' => ''];
    echo json_encode($message);
    return;
} else {
    $message = ['status' => 'error', 'message' => 'Ocorreu um problema', 'redirect' => ''];
    echo json_encode($message);
    return;
}

?>