<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "GG Banners";

include_once realpath(__DIR__ . '/../controllers/c-usuarios.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Usuários
            </h3>
            ";
            if (isset($_SESSION['logged']) && $_SESSION['nivel'] > 2){
                $string .= "
                <div class= 'text-start'>
                    <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#meuModal'>
                        <i class='lni lni-plus'></i> Adicionar
                    </button>
                </div>";
            $string .=gerarModalForm('meuModal', 'Adicionar Usuário');
            }
            ?>
<?php
    $string.= "   
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