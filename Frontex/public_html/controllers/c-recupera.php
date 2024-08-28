<?php
require '../connection/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obter e filtrar dados do POST
$post = filter_input_array(INPUT_POST, [
    'email' => FILTER_SANITIZE_EMAIL,
    'action' => FILTER_SANITIZE_SPECIAL_CHARS
]);

// Filtrando dados
$email = isset($post['email']) ? strip_tags($post['email']) : null;

// Mensagem inicial
$message = null;

// Verificar se o e-mail está vazio ou não é válido
if (!$email || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = ['status' => 'info', 'message' => 'Seu email não é válido', 'redirect' => ''];
    echo json_encode($message);
    return;
}

// Verificar se o email existe na base de dados
$read = $pdo->prepare("SELECT email FROM usuarios WHERE email = :email");
$read->bindValue(':email', $email);
$read->execute();

// Obter o número de linhas retornadas
$lines = $read->rowCount();

if ($lines == 0) {
    $message = ['status' => 'info', 'message' => 'Email está incorreto ou não cadastrado', 'redirect' => ''];
    echo json_encode($message);
    return;
} 

// Configuração do PHPMailer
$Subject = '[Recuperação de Senha] Recuperação de Senha';
$Body = "
<h1>Recuperação de Senha</h1>
<p><a href='http://localhost:8081/TCC%20Desenvolvimento/gg/view/v-nova-senha.php?email=" . urlencode($email) . "' target='_blank'>Clique aqui para mudar a senha</a></p>
";

require "../../../phpmail/vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../../../phpmail/vendor/phpmailer/phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = MAIL_HOST;
$mail->SMTPAuth = true;
$mail->SMTPSecure = MAIL_SECURE;
$mail->Username = MAIL_USER;
$mail->Password = MAIL_PASS;
$mail->Port = MAIL_PORT;
$mail->isHTML(true);
$mail->CharSet = "utf-8";

$mail->addReplyTo(MAIL_RESPONSE);
$mail->setFrom(MAIL_RESPONSE, 'Recuperador de Senha');
$mail->addAddress($email);
$mail->addBCC(MAIL_RESPONSE);

$mail->Subject = $Subject;
$mail->Body = $Body;

// Enviar o email
if ($mail->send()) {
    $message = ['status' => 'success', 'message' => 'Instruções para recuperação de senha foram enviadas para o seu email.', 'redirect' => ''];
} else {
    $message = ['status' => 'error', 'message' => 'Não foi possível enviar o email. Tente novamente mais tarde.', 'redirect' => ''];
}

// Exibir mensagem JSON
echo json_encode($message);
?>
