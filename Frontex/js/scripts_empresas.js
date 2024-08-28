
    //MÁSCARAS
    $(document).ready(function() {
        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('.money').mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
        $('.phone').mask('(00) 0000-0000#', {translation: {'#': {pattern: /[0-9]/, optional: true}}});
        $('.datetime').mask('00/00/0000 00:00', {placeholder: 'dd/mm/aaaa hh:mm'});
    });