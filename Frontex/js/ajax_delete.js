$('[id^="btn_delete"]').on('click', function(e) {
    e.preventDefault();

    var caminho = window.location.pathname; 
    var nomeArquivo = caminho.substring(caminho.lastIndexOf('/') + 1); 
    var nomeBase = nomeArquivo.replace('.php', '');
    var tabela = nomeBase.substring(2);

    console.log(tabela);

    var $button = $(this);
    var href = $button.attr('href');
    
    // Verifica se href está definido e contém o valor esperado
    if (href) {
        var idMatch = href.match(/\?delete=(\d+)/);
        
        if (idMatch) {
            var id = idMatch[1]; // Extraí o ID da correspondência

            var url = '../scripts/deletar_registros.php';
            
            $.ajax({
                url: url,
                type: 'POST',
                data: { 
                    id: id, 
                    tabela: tabela 
                },
                dataType: 'JSON',

                success: function(data, textStatus, jqXHR) {
                    console.log("Sucesso:", data);

                    $(".result").text(''); // Limpa o conteúdo existente

                    // Cria a mensagem de status com base no 'status' da resposta
                    var statusClass = data['status'];
                    var statusMessage = data['message'];
                    var statusHtml = `
                        <div class="status-top-right text-center" id="status-container">
                            <div class="status status-${statusClass}">
                                <div class="status-message">${statusMessage}</div>
                            </div>
                        </div>
                    `;
                    $(".result").prepend(statusHtml);
                    window.location.href = data['redirect'];
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX:", textStatus, errorThrown);
                    console.log("Resposta do servidor:", jqXHR.responseText);
                }
            });
        } else {
            console.error("ID não encontrado na URL.");
        }
    } else {
        console.error("Href não encontrado.");
    }
});
