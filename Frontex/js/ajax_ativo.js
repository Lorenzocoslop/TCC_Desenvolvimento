$(document).ready(function() {
    var caminho = window.location.pathname; 
    var nomeArquivo = caminho.substring(caminho.lastIndexOf('/') + 1); 
    var nomeBase = nomeArquivo.replace('.php', '');
    var tabela = nomeBase.substring(2);



    function atualizarEstiloativo() {
        $('button[id^="ativo"]').each(function() {
            var $button = $(this);
            var ativo = parseInt($button.val());
            var id = $button.attr('id').split('_')[1]; 
    
  
            var $tr = $button.closest('tr'); 
            var $tdAtivo = $tr.find('#row_ativo'); 
            

            var $botaoAtivo = $tdAtivo.find('button[id="ativo_' + id + '"]');
    
            if (ativo == 1) {

                $botaoAtivo.html("<i class='lni lni-checkmark-circle text-success'></i>");
            } else {

                $botaoAtivo.html("<i class='lni lni-cross-circle text-danger'></i>");
            }
        });
    }
    


    atualizarEstiloativo();

    $('button[id^="ativo"]').on('click', function() {
        var $button = $(this);
        var ativo = parseInt($button.val());
        var novoAtivo = ativo === 1 ? 0 : 1; 

        $button.val(novoAtivo);
        atualizarEstiloativo();

        var id = $button.closest('tr').find('a').attr('href').match(/#formModal(\d+)/)[1];

        $.ajax({
            url: '../scripts/atualizar_ativo.php',
            type: 'POST',
            data: {
                tabela : tabela,
                id: id,
                ativo: novoAtivo
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (!data.success) {
                    alert('Erro ao atualizar: ' + data.message);
                }
            },
            error: function() {
                alert('Erro na solicitação.');
            }
        });
    });
});
