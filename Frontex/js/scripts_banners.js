
    //MÁSCARAS
    $(document).ready(function() {
        $('.cpf').mask('000.000.000-00', {reverse: true});
        $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        $('.money').mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
        $('.phone').mask('(00) 0000-0000#', {translation: {'#': {pattern: /[0-9]/, optional: true}}});
        $('.datetime').mask('00/00/0000 00:00', {placeholder: 'dd/mm/aaaa hh:mm'});
    });

    //IMAGE PREVIEW
    function previewImage() {
        const file = document.getElementById('img').files[0];
        const preview = document.getElementById('imgPreview');
        const imagePreview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Exibe a imagem
            };

            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none'; // Oculta a imagem se nenhum arquivo for selecionado
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const imgPath = document.getElementById('imgPath') ? document.getElementById('imgPath').value : '';
        if (imgPath) {
            const preview = document.getElementById('imgPreview');
            preview.src = imgPath;
            preview.style.display = 'block';
        }
    });

        