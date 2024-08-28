<?php
require_once '../../gg/connection/config.php';

$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_SPECIAL_CHARS);
$postfilter = array_map('strip_tags', $post);


$message = null;

$counter = 0;

if ($counter == TIMESBLOCKED) {
    $_SESSION['counter'] = $counter + 1;
    $message = ['status' => 'warning', 'message' => 'Você possui mais uma tentativa', 'redirect' => ''];
    echo json_encode($message);
    return;
}



if (BLOCKED == 1 && $counter == TIMESBLOCKED || (isset($_SESSION['blocked']) && $_SESSION['blocked'] == 1)) {
    unset($_SESSION['nome']);
    unset($_SESSION['nivel']);
    unset($_SESSION['ID_empresa']);
    unset($_SESSION['email']);
    unset($_SESSION['ID']);
    unset($_SESSION['logged']);
    
    $_SESSION['blocked'] = 1;
    setcookie("LBlocked",1,86400,'/');

    $message = ['status' => 'error', 'message' => 'Seu acesso foi bloqueado', 'redirect' => ''];
    echo json_encode($message);
    return;
}

$email = $postfilter['email'] ?? '';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = ['status' => 'info', 'message' => 'Seu email não é válido', 'redirect' => ''];
    echo json_encode($message);
    exit;
    
}

$read = $pdo->prepare("SELECT ID, nome, email, senha, nivel, ID_empresa FROM usuarios WHERE email = :email");
$read -> bindvalue(':email', $email);
$read -> execute();


$lines = $read -> rowcount();

if ($lines == 0) {
    $_SESSION['counter'] = $counter + 1;
    if($counter == TIMESBLOCKED){
        $message = ['status' => 'warning', 'message' => 'Você possui mais uma tentativa', 'redirect' => ''];
        echo json_encode($message);
        return;
    } else {
        $message = ['status' => 'info', 'message' => 'Email ou senha incorretos', 'redirect' => ''];
        echo json_encode($message);
        return;
    }
} 

foreach($read as $show){}

$verificasenha = password_verify($postfilter['senha'], $show['senha']);

if($verificasenha){
    if(!empty($postfilter['nova_senha'])){
        $time = 3600*24*30;
        $email = $postfilter['email'];
        $senha = $postfilter['senha'];
        setcookie("LE",$email, $time, '/');
        setcookie("LP",$senha, $time, '/');
    }

    $_SESSION['ID'] = $show['ID'];
    $_SESSION['nome'] = $show['nome'];
    $_SESSION['email'] = $show['email'];
    $_SESSION['senha'] = $show['senha'];
    $_SESSION['nivel'] = $show['nivel'];
    $_SESSION['ID_empresa'] = $show['ID_empresa'];
    $_SESSION['logged'] = 1;
    unset($_SESSION['counter']);

    $message = ['status' => 'success', 'message' => 'Logado com sucesso', 'redirect' => '../../public_html/view/v-home.php'];
        echo json_encode($message);
        return;

} else {
    $message = ['status' => 'info', 'message' => 'Email ou senha incorretos', 'redirect' => ''];
    echo json_encode($message);
    return;
}

?>