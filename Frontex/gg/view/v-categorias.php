<?php

include_once realpath(__DIR__ . '/../../gg/connection/connection.php');

$title = "GG Lojas";

include_once realpath(__DIR__ . '/../controllers/c-categorias.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Categorias
            </h3>
            <div class= 'text-start'>
                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#meuModal'>
                    <i class='lni lni-plus'></i>
                </button>
            </div>
            ";?>
<?php
$string .= 
gerarModalForm('meuModal', 'Adicionar Categoria').

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
    

    include_once realpath(__DIR__ . '/../templates/template.php'); 
?>