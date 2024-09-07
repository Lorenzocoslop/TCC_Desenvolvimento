<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "GG Empresas";

include_once realpath(__DIR__ . '/../controllers/c-empresas.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Empresas
            </h3>
            <div class= 'text-start'>
                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#meuModal'>
                    <i class='lni lni-plus'></i> Adicionar
                </button>
            </div>
            ";?>
<?php
$string .= 
gerarModalForm('meuModal', 'Adicionar Empresa').

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