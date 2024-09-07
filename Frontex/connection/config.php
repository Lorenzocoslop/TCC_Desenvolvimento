<?php
//Iniciando a Sessão em Toda Nossa Aplicação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Configurando o Timezone e a Data Hora do Nosso Servidor
date_default_timezone_set("America/Sao_paulo");


//Chamada da Conexão
require_once realpath(__DIR__ . '../../connection/connection.php');


/* Configurações de Níveis de Acesso */
define("LEVEL_USER", 1); //Nível de Acesso Para Usuários [Operacionais]
define("LEVEL_CLIENT", 2); //Nível de Acesso Para Clientes [Coordenadores de Equipes]
define("LEVEL_ADMIN", 9); //Nível de Acesso Para Administradores [Administrador Responsável pela Aplicação]
define("LEVEL_SUPER", 10); //Nível de Acesso Para Profissional Web [Você]

/* Configurações de Servidor de E-mail */
define("MAIL_HOST", "smtp.gmail.com"); // Servidor SMTP do Gmail
define("MAIL_SMTP", "smtp.gmail.com"); // Endereço SMTP
define("MAIL_USER", "lolocolop2@gmail.com"); // Seu endereço de email do Gmail
define("MAIL_PASS", "181920Jogamuito1234!@"); // Sua senha do Gmail ou senha de app se tiver 2FA ativado
define("MAIL_RESPONSE", "lolocolop2@gmail.com"); // Endereço de resposta
define("MAIL_PORT", 465); // Porta para SSL
define("MAIL_SECURE", "ssl"); // Segurança SSL

/*Configurações de Módulos*/
define('BLOCKED', 1); //Bloqueio o Usuário Após 6 Tentativas de Senha Errado
define('TIMESBLOCKED', 5); //Quantas Tentativas Usuário Pode Fazer Antes de Bloquear
define('REMEMBER', 1); //Lembrar Senha
define('TITLE_LOGIN', 'Login Auth 2.0'); //Nome da Aplicação
define('LOGINACTIVE', 1); //Login Ativo - Módulo Possibilita Acesso Direto, Se Houver Cookies. Para Funcionar Precisa do Remember Ativo.
define('LOGCREATE', 1); //Cria Log com .txt de Login (NOT APPLICATED)
define('LOGINHISTORY', 1); //Cria Histórico de Login - Salve no Banco de Dados. (NOT APPLICATED)