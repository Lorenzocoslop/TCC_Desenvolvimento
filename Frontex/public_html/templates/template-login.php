<?php
ob_start();
include_once realpath(__DIR__ . '/../../gg/connection/connection.php');

require '../../model/path.php';

// Verifique se o cookie 'LBlocker' existe
if(isset($_COOKIE['LBlocker']) && !empty($_COOKIE['LBlocker'])){
    $_SESSION['blocked'] = 1;
    $_SESSION['counter'] = TIMESBLOCKED;
}

// Verifique se os cookies 'LE' e 'LP' existem
if (isset($_COOKIE['LE']) && isset($_COOKIE['LP']) && !empty($_COOKIE['LE']) && !empty($_COOKIE['LP'])) {
    header('location: ../controllers/c-active.php');
    exit(); // Use exit para garantir que o script pare após o redirecionamento
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Login' ?></title> <!-- Verifica se $title está definido -->
    <link rel="stylesheet" href="../../gg/css/common.css">
    <link rel="stylesheet" href="../../gg/css/view.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <script src='https://code.jquery.com/jquery-3.7.1.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js'></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <main <?= (isset($_COOKIE['LE']) && ($_COOKIE['LE'] != '' || $_COOKIE['LE'] != NULL) ? 'id="body_register"' : '')?>>
        <?=$string?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../../js/ajax_login.js"></script>
    <script src="../../js/view.js"></script>
    <?php if ($pagina_atual === 'v-cadastro'): ?>
        <script src="../../js/ajax_cadastro.js"></script>
    <?php endif; ?>
    
</body>

</html>
<?php
ob_end_flush();
?>
