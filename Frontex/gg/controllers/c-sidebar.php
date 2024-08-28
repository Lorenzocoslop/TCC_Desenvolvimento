<?php
$sidebar = "
        <aside id='sidebar'>
            <div class='d-flex'>
                <div class='sidebar-logo w-100 h-100 text-center m-1'>
                    <a href='../view/v-home.php'><img class = 'img-fluid' style='width: 150px; height: 50px;' src='../../brand/logo.png' alt='Logo'></img></a> 
                </div>
            </div>
            <ul class='sidebar-nav'>
                <li class='sidebar-item'>
                    <a href='/TCC_Desenvolvimento/public_html/view/v-home.php' target='_blank' class='sidebar-link'>
                        <i class='lni lni-flag'></i>
                        <span>Meu Site</span>
                    </a>
                    <hr class='divider'></hr>
                </li>
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
            </ul>
            <div class='sidebar-footer'>
                <a href='?action=logout' class='sidebar-link' id='logout'>
                    <i class='lni lni-exit'></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>"
?>