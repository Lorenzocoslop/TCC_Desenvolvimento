$(document).ready(function() {
    $('#form_register').on('submit', function(e) {
        e.preventDefault(); // Evita o envio padrão do formulário
        
        // Coleta os dados do formulário
        var formData = {
            nome: $('#register_nome').val(),
            email: $('#register_email').val(),
            senha: $('#register_password').val()
        };
        
        $.ajax({
            url: '../controllers/c-cadastro.php', // Altere para o caminho correto do seu script PHP
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Manipule a resposta do servidor aqui
                if (response.status === 'success') {
                    $('.result').html('<div class="status-top-right text-center" id="status-container"><div class="status status-success"><div class="status-message">'+response.message+'</div></div></div>');
                } else {
                    $('.result').html('<div class="status-top-right text-center" id="status-container"><div class="status status-error"><div class="status-message">'+response.message+'</div></div></div>');
                }
                
                setTimeout(function(){
                    $("#status-container").hide();
                        window.location.href = 'v-login.php';
                }, 3000);
            },
            error: function() {
                $('.result').html('<div class="status-top-right text-center" id="status-container"><div class="status status-error"><div class="status-message">'+response.message+'</div></div></div>');
            }
        });
    });
});