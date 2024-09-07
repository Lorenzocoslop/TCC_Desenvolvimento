<?php
require_once '../../model/path.php';
require_once '../../connection/config.php'; 
if (
    !isset($_SESSION['nome']) || 
    !isset($_SESSION['nivel']) ||
    !isset($_SESSION['ID_empresa']) || 
    !isset($_SESSION['email']) || 
    !isset($_SESSION['ID']) || 
    !isset($_SESSION['logged']) || 
    ($_SESSION['logged'] && $_SESSION['nivel'] < 2) || 
    (isset($_SESSION['blocked']) && $_SESSION['blocked'] == 1)
) {
    session_destroy();
    unset($_SESSION['nome']);
    unset($_SESSION['nivel']);
    unset($_SESSION['ID_empresa']);
    unset($_SESSION['email']);
    unset($_SESSION['ID']);
    unset($_SESSION['logged']);
    header('location: ../view/v-login.php');
    exit();
}

if (isset($_SESSION['status_message'])) {
    $status_message = $_SESSION['status_message'];
    $status_class = $_SESSION['status_class'];
    echo "<div class='status-top-right text-center' id='status-container'>
            <div class='status status-$status_class'>
                <div class='status-message'>$status_message</div>
            </div>
          </div>";
    unset($_SESSION['status_message']);
    unset($_SESSION['status_class']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/view.css">
    <link href="../../css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/common.css">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
    <div class='result'></div>
    <main>
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
    
        <div class="wrapper">
            <aside id='sidebar'>

                <div class='d-flex'>
                    <div class='sidebar-logo w-100 h-100 text-center m-1'>
                        <a href='../view/v-home.php'><img class = 'img-fluid' style='width: 150px; height: 50px;' src='../../brand/logo.png' alt='Logo'></img></a> 
                    </div>
                </div>

                <ul class='sidebar-nav'>
                    <li class='sidebar-item'>
                        <a href='../../public_html/view/v-home.php' target='_blank' class='sidebar-link'>
                            <i class="lni lni-world"></i>
                            <span>Meu Site</span>
                        </a>
                    </li>

                    <li class='sidebar-item'>
                        <a href='../view/v-home.php' class='sidebar-link'>
                            <i class='lni lni-home'></i>
                            <span>In&iacute;cio</span>
                        </a>
                    </li>

                    <hr class='divider'></hr>
                    <li class='sidebar-item'>
                        <a href='v-banners.php' class='sidebar-link'>
                            <i class='lni lni-layout'></i>
                            <span>Banners</span>
                        </a>
                    </li>

                    <li class='sidebar-item'>
                        <a href='v-produtos.php' class='sidebar-link'>
                            <i class='lni lni-shopping-basket'></i>
                            <span>Produtos</span>
                        </a>
                    </li>

                    <li class='sidebar-item'>
                        <a href='v-cupons.php' class='sidebar-link'>
                            <i class="lni lni-ticket"></i>
                            <span>Cupons</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 4):?>
                    <li class='sidebar-item'>
                        <a href='v-categorias.php' class='sidebar-link'>
                            <i class="lni lni-tag"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class='sidebar-item'>
                        <a href='v-banners.php' class='sidebar-link'>
                            <i class="lni lni-cart"></i>
                            <span>Pedidos</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 4):?>
                    <li class='sidebar-item'>
                        <a href='v-empresas.php' class='sidebar-link'>
                            <i class="lni lni-blackboard"></i>
                            <span>Empresas</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['logged']) && $_SESSION['nivel'] >= 3):?>
                    <hr class='divider'></hr>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['logged']) && $_SESSION['nivel'] >= 3):?>
                    <li class='sidebar-item'>
                        <a href='v-usuarios.php' class='sidebar-link'>
                            <i class="lni lni-users"></i>
                            <span>Usu&aacute;rios</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['logged']) && $_SESSION['nivel'] == 3):?>
                    <li class='sidebar-item'>
                        <a href='v-configuracoes.php' class='sidebar-link'>
                            <i class="lni lni-restaurant"></i>
                            <span>Configura&ccedil;&otilde;es</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class='sidebar-footer'>
                    <a href='?action=logout' class='sidebar-link' id='logout'>
                        <i class='lni lni-exit'></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>
            <div class="main p-3">
                <div class="text-start">
                    <button class="toggle-btn" type="button">
                    <i class="lni lni-menu text-black"></i>
                    </button>
                </div>
                <?=$string?>
            </div>
        </div>
    </main>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../../js/view.js"></script>
    
    <?php if ($pagina_atual === 'v-empresas'): ?>
        <script src="../../js/scripts_empresas.js"></script>
        <script src="../../js/ajax_ativo.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-cupons'): ?>
        <script src="../../js/scripts_cupons.js"></script>
        <script src="../../js/ajax_ativo.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-configuracoes'): ?>
        <script src="../../js/scripts_empresas.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-usuarios'): ?>
        <script src="../../js/scripts_usuarios.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-categorias'): ?>
        <script src="../../js/scripts_categorias.js"></script>
        <script src="../../js/ajax_ativo.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-produtos'): ?>
        <script src="../../js/ajax_produtos.js"></script>
        <script src="../../js/scripts_produtos.js"></script>
        <script src="../../js/ajax_ativo.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-banners'): ?>
        <script src="../../js/scripts_banners.js"></script>
        <script src="../../js/ajax_ativo.js"></script>
        <script src="../../js/ajax_delete.js"></script>
        <script src="../../js/validacao.js"></script>
    <?php endif; ?>

    <?php if ($pagina_atual === 'v-home'): ?>
        <script src="../../js/scripts_banners.js"></script>
    <?php endif; ?>
</body>

</html>
