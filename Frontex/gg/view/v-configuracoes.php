<?php

include_once realpath(__DIR__ . '/../../gg/connection/connection.php');

$title = "GG Empresas";

include_once realpath(__DIR__ . '/../controllers/c-configuracoes.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Configura&ccedil;&otilde;es da Loja
            </h3>
        </div>";
$string .= 
gerarFormAtualizado($obj);
    

    include_once realpath(__DIR__ . '/../templates/template.php'); 
?>