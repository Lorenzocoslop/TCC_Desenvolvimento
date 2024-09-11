$(document).ready(function() {

    function atualizarNotificacoes() {
        $.ajax({
            url: '../model/att_notificacoes.php',
            type: 'POST',
            data: { tipo: 'atualizar_notificacoes' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var qtdItens = response.notificacoes.notificacoes;
                    
                    if (qtdItens > 0) {
                        $('#notificacoes').html(qtdItens);
                        $('#notificacoes').show();
                    } else {
                        $('#notificacoes').hide();
                    }
                } else {
                    alert('Erro ao atualizar as notificações. Tente novamente.');
                }
            },
            error: function() {
                alert('Erro ao se comunicar com o servidor.');
            }
        });
    }

    $('.btn-comprar').on('click', function() {
        var produtoId = $(this).data('id');
        var itemId = '#itemProduto_' + produtoId; // Cria o seletor do ID da linha

        $.ajax({
            url: '../model/add_produto_carrinho.php',
            type: 'POST',
            data: { ID_produto: produtoId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    atualizarNotificacoes();
                    if (window.location.pathname.includes('v-carrinho')) {
                        location.reload();
                    }
                } else {
                    alert('Erro ao excluir o produto. Tente novamente.');
                }
            },
            error: function() {
                alert('Erro ao se comunicar com o servidor.');
            }
        });
    });
});