<?php

include_once realpath(__DIR__ . '/../../gg/connection/connection.php');

$title = "GG Cupons";

include_once realpath(__DIR__ . '/../controllers/c-cupons.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Cupons
            </h3>
            <div class= 'text-start'>
                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#meuModal'>
                    <i class='lni lni-plus'></i> Adicionar
                </button>
            </div>
            ";?>
<?php
$string .= 
gerarModalForm('meuModal', 'Adicionar Cupom').

    "   
        <table class='table table-striped' id = 'tabelaCupons' >
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