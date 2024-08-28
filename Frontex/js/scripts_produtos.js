
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


    //MODAIS DE ATIVO
    document.addEventListener('DOMContentLoaded', function () {

        var modals = document.querySelectorAll('.modal');
    
        modals.forEach(function (modal) {
            modal.addEventListener('show.bs.modal', function (event) {

                var modalElement = event.target;
    
                var radio1 = modalElement.querySelector('input[type="radio"][value="1"]');
                var radio0 = modalElement.querySelector('input[type="radio"][value="0"]');
    
                if (radio1 && radio0) {
                    if (radio1.checked) {
                        radio1.checked = true;
                        radio0.checked = false;
                    } else {
                        radio1.checked = false;
                        radio0.checked = true;
                    }
                }
            });
        });
    });
    

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('codigobarra').style.display = 'none';
        document.getElementById('label_codigobarra').style.display = 'none';
    });


    document.addEventListener('DOMContentLoaded', function() {
        const codigobarraField = document.getElementById('codigobarra');
        const codigobarraValue = codigobarraField.value.trim();
        let valor = codigobarraValue === "" ? 0 : parseInt(codigobarraValue);
        let id = codigobarraValue === "" ? 0 : parseInt(codigobarraValue);

        // Seleciona a opção correta com base no valor
        const optionCodigo = document.getElementById('tem_codigo_' + valor + '_');
        if (optionCodigo) {
            optionCodigo.checked = true;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const codigobarraField = document.getElementById('codigobarra');
        const codigobarraValue = codigobarraField.value.trim();
        let valor = codigobarraValue === "" ? 0 : parseInt(codigobarraValue);

        // Seleciona a opção correta com base no valor
        const optionCodigo = document.getElementById('tem_codigo_' + valor + '_');
        if (optionCodigo) {
            optionCodigo.checked = true;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const codigobarraField = document.getElementById('codigobarra');
        const codigobarraValue = codigobarraField.value.trim();
        let valor = codigobarraValue === "" ? 0 : 1;  // Define 1 se houver código de barras, caso contrário 0.
    
        // Seleciona a opção correta com base no valor
        const optionCodigo = document.getElementById('tem_codigo_' + valor +'_');
        if (optionCodigo) {
            optionCodigo.checked = true;
        }
    
        // Exibe ou oculta o campo de código de barras com base no valor
        if (valor == 1) {
            codigobarraField.style.display = 'block';
            document.getElementById('label_codigobarra').style.display = 'block';
        } else {
            codigobarraField.style.display = 'none';
            document.getElementById('label_codigobarra').style.display = 'none';
        }
    });

    //ESCONDE LABEL
    document.addEventListener('DOMContentLoaded', function() {
        const idField = document.getElementById('idhidden');
            const idValue = idField.value.trim();
            let id = idValue;
        

        document.getElementById('tem_codigo_1_' + id).addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('codigobarra').style.display = 'block';
                document.getElementById('label_codigobarra').style.display = 'block';
            }
        });
    
        document.getElementById('tem_codigo_0_'+ id).addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('codigobarra').style.display = 'none';
                document.getElementById('label_codigobarra').style.display = 'none';
            }
        });
    });