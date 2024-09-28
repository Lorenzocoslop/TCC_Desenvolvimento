<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "GG Pedidos";

include_once realpath(__DIR__ . '/../controllers/c-pedidos.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Pedidos
            </h3>
        </div>
            ";?>
<?php
$string .= 
    "   
        <table class='table table-striped' >
            ";?>

        <?php
            
            $string.= gerarThead($dados);
            $string.= gerarTbody($obj);
            
        ?>

        <?php
$string.="</table>";
$string.= gerarModaisTabela($obj);

$string.="
<script src='../../js/ajax_alterar_status.js'></script>
";

    include_once realpath(__DIR__ . '/../templates/template.php'); 
?>