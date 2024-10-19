<?php
include_once realpath(__DIR__ . '/../../connection/connection.php');
include_once realpath(__DIR__ . '/../../model/path.php');

$view['quant_pedidos'] = $pdo->prepare('SELECT * FROM pedidos');
