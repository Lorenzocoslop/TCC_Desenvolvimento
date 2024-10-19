<?php

function conectar()
{
    $server = 'localhost';
    $db = 'base';
    $usuario = 'root';
    $senha = '';
    $porta= '3306';

    try {
        $pdo = new PDO("mysql:host=$server;port=$porta;dbname=$db;charset=utf8", $usuario, $senha);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
        exit;
    }

    return $pdo;
}

$pdo = conectar();
?>