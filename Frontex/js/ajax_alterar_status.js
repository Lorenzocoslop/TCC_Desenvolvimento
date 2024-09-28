$(document).on('click', 'a[id^="btn_status"]', function() {
    var tipo = $(this).data('status');
    var id = $(this).data('id');
    var linha = $(this).closest('tr'); // Armazena a referência da linha

    $.ajax({
        url: '../scripts/status-pedido.php',
        type: 'POST',
        data: { 
            tipo: tipo, 
            id: id 
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var novoStatus = '';
                switch(response.status) {
                    case 2:
                        novoStatus = "<a class='btn btn-warning' id='btn_status_" + response.id + "' data-id='" + response.id + "' data-status='finalizar'>Finalizar</a>"
                                   + "<a class='btn btn-danger' id='btn_status_" + response.id + "' data-id='" + response.id + "' data-status='recusar'>Recusar</a>";
                        break;
                    case 3:
                        novoStatus = "<p class='text-success'>Finalizado</p>";
                        break;
                    case 4:
                        novoStatus = "<p class='text-danger'>Cancelado</p>";
                        break;
                }

                // Substituir o conteúdo da célula de status na linha correspondente
                linha.find('#row_status_' + response.id).html(novoStatus);

            } else {
                alert('Erro ao processar a solicitação. Tente novamente.');
            }
        },
        error: function() {
            alert('Erro ao se comunicar com o servidor.');
        }
    });
});