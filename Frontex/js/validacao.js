$('[id^="btn_submit_banners_"]').on('click', function(e) {
    e.preventDefault();

    var nomeField = $(this).closest('form').find('input[name="nome"]');
    var imagemField = $(this).closest('form').find('input[name="img"]');

    if (nomeField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O nome precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else {
        var imagemVal = imagemField.val();
        if (imagemVal) {
            var extensao = imagemVal.split('.').pop().toLowerCase();
            if (extensao !== 'jpg' && extensao !== 'jpeg' && extensao !== 'png') {
                var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>A imagem precisa estar no formato JPG ou JPEG</div></div></div>";
                $('body').append(errorMessage);
                setTimeout(function() {
                    $('#status-container').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
                return; // Não submeter o formulário
            }
        }
        // Se tudo estiver OK, submete o formulário
        $(this).closest('form').submit();
    }
});


$('[id^="btn_submit_produtos_"]').on('click', function(e) {
    e.preventDefault();

    var nomeField = $(this).closest('form').find('input[name="nome"]');
    var preco_vendaField = $(this).closest('form').find('input[name="preco_venda"]');
    var imagemField = $(this).closest('form').find('input[name="img"]');

    if (nomeField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O nome precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else if (preco_vendaField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O pre&ccedil;o precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else {
        var imagemVal = imagemField.val();
        if (imagemVal) {
            var extensao = imagemVal.split('.').pop().toLowerCase();
            if (extensao !== 'jpg' && extensao !== 'jpeg') {
                var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>A imagem precisa estar no formato JPG ou JPEG</div></div></div>";
                $('body').append(errorMessage);
                setTimeout(function() {
                    $('#status-container').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
                return; // Não submeter o formulário
            }
        }
        // Se tudo estiver OK, submete o formulário
        $(this).closest('form').submit();
    }
});

$('[id^="btn_submit_empresas_"]').on('click', function(e) {
    e.preventDefault();

    var nomeField = $(this).closest('form').find('input[name="nome"]');
    var cnpjField = $(this).closest('form').find('input[name="cnpj"]');

    if (nomeField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O nome precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else if (cnpjField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O CNPJ precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else if (cnpjField.val().length < 18) {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O CNPJ precisa ser preenchido completamente</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else {
        $(this).closest('form').submit();
    }
});

$('[id^="btn_submit_categorias_"]').on('click', function(e) {
    e.preventDefault();

    var nomeField = $(this).closest('form').find('input[name="nome"]');
    var imagemField = $(this).closest('form').find('input[name="img"]');

    if (nomeField.val().trim() === '') {
        var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>O nome precisa ser preenchido</div></div></div>";
        $('body').append(errorMessage);
        setTimeout(function() {
            $('#status-container').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    } else {
        var imagemVal = imagemField.val();
        if (imagemVal) {
            var extensao = imagemVal.split('.').pop().toLowerCase();
            if (extensao !== 'jpg' && extensao !== 'jpeg') {
                var errorMessage = "<div class='status-top-right text-center' id='status-container'><div class='status status-error'><div class='status-message'>A imagem precisa estar no formato JPG ou JPEG</div></div></div>";
                $('body').append(errorMessage);
                setTimeout(function() {
                    $('#status-container').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
                return; // Não submeter o formulário
            }
        }
        // Se tudo estiver OK, submete o formulário
        $(this).closest('form').submit();
    }
});