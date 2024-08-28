<?php
include_once "../resources/assets/cores.php";
require_once '../../gg/connection/config.php'; 

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="../css/common.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <body>
        <?php
        $action = strip_tags(filter_input(INPUT_GET,'action', FILTER_SANITIZE_SPECIAL_CHARS));
        if($action == 'logout'){
            session_destroy();
            unset($_SESSION['nome']);
            unset($_SESSION['nivel']);
            unset($_SESSION['ID_empresa']);
            unset($_SESSION['email']);
            unset($_SESSION['ID']);
            unset($_SESSION['logged']);
            header('location: ../view/v-login.php');
        }?>

<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark">
    <div class="container-fluid">

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../../brand/logo_alt.png" class="img-fluid" alt="Logo" width="150" height="50" class="d-inline-block align-text-top">
        </a>
        <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] === 1): ?>
            <small class="saudacao">Olá, <?=$_SESSION['nome']?></small>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
            <div class="d-flex justify-content-center flex-grow-1">
                <form class="d-flex col-lg-6 col-md-8 col-sm-10" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn-outline-custom" type="submit">Pesquisar</button>
                </form>
            </div>

            <div class="d-flex align-items-center ms-auto" id="navbarSupportedContent">
                <a class="navbar-brand" href="v-carrinho.php">
                    <i class="material-icons color-primary" style="color: <?=$primaria?>; font-size: 35px">local_mall</i>
                </a>
            </div>
            <div class="d-flex align-items-center ms-auto" id="navbarSupportedContent">
                <a class="nav-link dropdown-toggle color-primary" style="color: <?=$primaria?>;" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="material-icons color-primary" style="color: <?=$primaria?>; font-size: 35px;">person</i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if (isset($_SESSION['logged']) && $_SESSION['logged'] === 1): ?>
                        <?php if ($_SESSION['nivel'] > 2): ?>
                            <li><a class="dropdown-item" href="../../gg/view/v-home.php">Painel</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Minhas compras</a></li>
                        <li><a class="dropdown-item" href="#">Meus Cupons</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a id="logout" class="dropdown-item" href="?action=logout">Sair</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="v-login.php">Faça seu Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>
        <?=$string?> 

    <footer>
        <p class="text-end">&copy; <?php echo date('Y'); ?> - Todos os direitos reservados.</p>
    </footer>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../js/carouselcategorias.js"></script>
    </body>
</html>