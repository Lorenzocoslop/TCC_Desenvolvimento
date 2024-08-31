
    //MÁSCARAS
    $(document).ready(function() {
        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('.money').mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
        $('.phone').mask('(00) 0000-0000#', {translation: {'#': {pattern: /[0-9]/, optional: true}}});
        $('.datetime').mask('00/00/0000 00:00', {placeholder: 'dd/mm/aaaa hh:mm'});
    });


    // document.addEventListener('DOMContentLoaded', function () {
    //     const rows = document.querySelectorAll('#tabelaCupons tbody tr');
    
    //     rows.forEach(row => {
    //         // Seleciona o elemento de data de expiração e status ativo pela estrutura de IDs
    //         const dataExpiracaoElement = row.querySelector('td[id^="dataExpiracao"]');
    //         console.log(dataExpiracaoElement);
    //         const statusAtivoElement = row.querySelector('button[id^="ativo"]');
    //         console.log(statusAtivoElement);
    
    //         // Verifica se os elementos existem
    //         if (!dataExpiracaoElement || !statusAtivoElement) {
    //             console.error('Elemento de data de expiração ou status ativo não encontrado.');
    //             return;
    //         }
    
    //         const dataExpiracao = dataExpiracaoElement.textContent.trim();
    //         console.log(dataExpiracao);
    
    //         // Acesse o valor diretamente do botão
    //         const statusAtivo = statusAtivoElement.getAttribute('data-status') || statusAtivoElement.textContent.trim();
    //         console.log(statusAtivo);
    
    //         const statusAtivoId = statusAtivoElement.id;
    //         console.log(statusAtivoId);
    //         const id = statusAtivoId ? statusAtivoId.split('_')[1] : ''; // Extrai o número após "ativo_"
    //         console.log(id);
    
    //         // Verifica se o ID e statusAtivo existem
    //         if (!id || !statusAtivo) {
    //             console.error(`ID ou statusAtivo não encontrados para a linha: ${row}`);
    //             return;
    //         }
    
    //         // Dividir a data no formato dd/mm/yyyy
    //         const [dia, mes, ano] = dataExpiracao.split('/');
    
    //         // Criar a data no formato ISO (yyyy-mm-dd) sem considerar o fuso horário
    //         const dataCupom = new Date(ano, mes - 1, dia); // Criando a data sem horas para evitar desvio por fuso
    //         // Pega a data atual sem horas
    //         const dataAtual = new Date();
    //         const hoje = new Date(dataAtual.getFullYear(), dataAtual.getMonth(), dataAtual.getDate());
    
    //         if (dataCupom < hoje && statusAtivo === "1") {
    //             console.log('acessou');
    //             // Enviar requisição Ajax para atualizar o status no banco de dados
    //             fetch('../scripts/checa_data_cupom.php', {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json'
    //                 },
    //                 body: JSON.stringify({ id: id })
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     // Atualiza o status na tabela (opcional)
    //                     statusAtivoElement.textContent = "0";
    //                 } else {
    //                     console.log(`Erro ao atualizar o cupom ID ${id}.`);
    //                 }
    //             })
    //             .catch(error => console.error('Erro na requisição:', error));
    //         }
    //     });
    // });