<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "GG Formas de Pagamento";

include_once realpath(__DIR__ . '/../controllers/c-formaspagamento.php');

include_once realpath(__DIR__ . '/../../model/form.class.php');


$string = "
        <div class='col-12 text-center'>
            <h3>
                Formas de Pagamento
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
            $string.= gerarTbody($obj, $pdo, $id_empresa);
            
        ?>

        <?php
$string.="</table>";

$string.="
<script>
$(document).ready(function() {
    $('.checkbox-formapagamento').change(function() {
        var id_formapagamento = $(this).val();
        var id_empresa = ".json_encode($_SESSION['ID_empresa']).";
        var isChecked = $(this).is(':checked');
        
        $.ajax({
            url: '../scripts/atualizar_formapagamento.php',
            type: 'POST',
            data: {
                id_formapagamento: id_formapagamento,
                id_empresa: id_empresa,
                action: isChecked ? 'inserir' : 'remover'
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>";

    include_once realpath(__DIR__ . '/../templates/template.php'); 
?>