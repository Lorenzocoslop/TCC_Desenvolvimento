$(document).ready(function() {
    // Função para aplicar estilo e marcação ao preço promocional
    function atualizarEstiloPrecoPromocao() {
        $('input[id^="campo"]').each(function() {
            var $input = $(this);
            var precoPromocao = parseFloat($input.val().replace(/[^\d.-]/g, ''));

            var $tr = $input.closest('tr');
            var $tdPrecoVenda = $tr.find('#row_preco_venda');

            if (precoPromocao > 0) {
                // Aplica a cor vermelha e adiciona a tag <s>
                $tdPrecoVenda.css('color', 'red');
                var textoPrecoVenda = $tdPrecoVenda.text();
                $tdPrecoVenda.html('<s>' + textoPrecoVenda + '</s>');
            } else {
                // Remove a cor vermelha e a tag <s> se o preço promocional for 0
                $tdPrecoVenda.css('color', '');
                var textoPrecoVenda = $tdPrecoVenda.text().replace(/<\/?s>/g, ''); // Remove a tag <s> se existir
                $tdPrecoVenda.html(textoPrecoVenda);
            }
        });
    }

    // Função para formatar o valor enquanto o usuário digita
    function formatarValorInput() {
        document.querySelectorAll("input[id^='campo_']").forEach(function(input) {
            input.addEventListener('input', function(event) {
                let value = input.value.replace(',', '').replace(/^0+/, ''); // Remove vírgula e zeros à esquerda
                let currentLength = value.length;
        
                // Ajuste para preservar o valor atual e formatar de acordo
                if (currentLength === 0) {
                    input.value = "0,00"; // Reseta para o valor inicial se nada for digitado
                } else if (currentLength === 1) {
                    input.value = `0,0${value}`;
                } else if (currentLength === 2) {
                    input.value = `0,${value}`;
                } else {
                    let formattedValue = `${value.slice(0, -2)},${value.slice(-2)}`;
                    input.value = formattedValue;
                }
        
                // Coloca o cursor no final do input
                input.selectionStart = input.selectionEnd = input.value.length;
            });

            // Função para permitir apenas números no input
            input.addEventListener('keypress', function(event) {
                let charCode = event.which ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault();
                }
            });
        });
    }

    // Atualiza o estilo quando a página é carregada
    atualizarEstiloPrecoPromocao();
    formatarValorInput(); // Aplica a formatação ao carregar a página

    // Evento para detectar mudanças no campo de preço promocional
    $('input[id^="campo"]').on('change', function() {
        var $input = $(this);
        var valorOriginal = $input.data('original-value'); // Valor original armazenado
        
        // Pega o valor já formatado pelo evento de input
        var precoPromocao = $input.val().replace(',', '.');

        var $tr = $input.closest('tr');
        var precoVenda = parseFloat($tr.find('#row_preco_venda').text().replace(/[^\d.-]/g, ''));

        // Verifica se o preço promocional é maior que o preço de venda
        if (parseFloat(precoPromocao) > precoVenda) {
            alert('O preço promocional não pode ser maior que o preço de venda.');
            $input.val(valorOriginal); // Restaura o valor original
            return; // Interrompe a execução da função
        }

        var id = $input.closest('tr').find('a').attr('href').match(/#formModal(\d+)/)[1];

        $.ajax({
            url: '../scripts/atualizar_preco_promocao.php',
            type: 'POST',
            data: {
                id: id,
                preco_promocao: precoPromocao
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    atualizarEstiloPrecoPromocao(); // Atualiza o estilo após sucesso
                } else {
                    alert('Erro ao atualizar: ' + data.message);
                }
            },
            error: function() {
                alert('Erro na solicitação.');
            }
        });
    });

    // Armazena o valor original do input quando a página carrega
    $('input[id^="campo"]').each(function() {
        $(this).data('original-value', $(this).val());
    });
});
