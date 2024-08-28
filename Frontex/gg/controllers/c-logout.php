<?php
require '../connection/config.php';

$get = filter_input_array(INPUT_GET, 'action' ,FILTER_SANITIZE_SPECIAL_CHARS);
$getfilter = array_map('strip_tags', $get);


$message = null;

if(!$get || $get == '' || $get == NULL){
    $message = ['status' => 'warning', 'message' => 'Erro no logout', 'redirect' => '../v-home.php'];
    echo json_encode($message);
    return;
} else{
    session_destroy();
    unset($_SESSION['nome']);
    unset($_SESSION['nivel']);
    unset($_SESSION['ID_empresa']);
    unset($_SESSION['email']);
    unset($_SESSION['ID']);
    unset($_SESSION['logged']);
    $message = ['status' => 'success', 'message' => 'Logout realizado com sucesso', 'redirect' => '../v-login.php'];
    echo json_encode($message);
    return;
}

?>