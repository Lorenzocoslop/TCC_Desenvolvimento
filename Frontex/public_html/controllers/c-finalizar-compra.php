<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../controllers/c-carrinho.php');
include_once realpath(__DIR__ . '/../controllers/c-usuarios.php');
include_once realpath(__DIR__ . '/../controllers/c-formaspagamento.php');
include_once realpath(__DIR__ . '/../../model/form.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    function listarProdutos(){
        $carrinho = new Carrinho();
        $dados = $carrinho->buscaPedidos();
        $string = Carrinho::listarProdutos($dados);

        return $string;
    }

    function totalPedido(){
        $carrinho = new Carrinho();
        $dados = $carrinho->buscaPedidos();
        $string = Carrinho::gerarTotalProdutos($dados);

        return $string;   
    }

    function gerarEnderecoForm($id = '') {
        $usuarios = new Usuario();
        $usuario = $usuarios->buscarUsuario();
        $string = "
        <form method='post' id='endereco_form'> 
            <input type='hidden' name='id' value='$id'>  
            <div class='conteiner'>
                <div class='row'>"; 
                    $string.= Form::InputText([
                        'size' => 6,
                        'name' => 'endereco',
                        'id' => 'endereco',
                        'label' => 'Rua',
                        'value' => $usuario['endereco'],
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'bairro',
                        'id' => 'bairro',
                        'label' => 'Bairro',
                        'value' => $usuario['bairro'],
                    ]);

                    $string.= Form::InputText([
                        'size' => 2,
                        'name' => 'numero',
                        'id' => 'numero',
                        'label' => 'N&uacute;mero',
                        'value' => $usuario['numero'],
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'cidade',
                        'id' => 'cidade',
                        'label' => 'Cidade',
                        'value' => $usuario['cidade'],
                    ]);

                    $estados = [];
                    $estados = Utils::listarEstados();

                    $string.= Form::select([
                        'size' => 4,
                        'name' => 'estado',
                        'id' => 'estado',
                        'options' => $estados,
                        'label' => 'Estado',
                        'value' => $usuario['estado'],
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'cep',
                        'id' => 'cep',
                        'label' => 'CEP',
                        'class' => 'cep',
                        'value' => $usuario['cep'],
                    ]);

                    $string.= Form::InputText([
                        'size' => 12,
                        'name' => 'complemento',
                        'id' => 'complemento',
                        'label' => 'Complemento',
                        'value' => $usuario['complemento'],
                    ]);
                    $string .=
                        "
                </div>
            </div>
        </form>
    ";
        return $string;
    }

    function gerarFormaPagamentoForm( $id = ''){
        $formapagamento = new FormaPagamento();
        $dados = $formapagamento->buscarFormasPagamentoEmpresa();
        $view['formaspagamento'] = [];
        foreach($dados as $formapagamento){
            $view['formaspagamento'][$formapagamento->ID] = $formapagamento->nome;
        }

        $string = "
        <form method='post'> 
            <input type='hidden' name='id' value='$id'>  
            <div class='conteiner'>
                <div class='row'>"; 
                    $string.= Form::select([
                        'size' => 12,
                        'name' => 'formapagamento',
                        'id' => 'formapagamento',
                        'label' => 'Selecione uma forma de pagamento',
                        'options' => $view['formaspagamento'],
                    ]);
                    $string .=
                        "
                </div>
            </div>
        </form>
    ";
        return $string;
    }

    function gerarAjax(){
        $carrinho = new Carrinho();
        $produtos = $carrinho->buscaPedidos();
        $usuario = (new Usuario())->buscarUsuario();
        
        $produtos_ids = array_map(fn($produto) => $produto->ID_produto, $produtos);
    
        $string = "
        <script>
            $(document).ready(function() {
                $('#finalizar_compra').on('click', function(e) {
                    e.preventDefault();
                    
                    var produtos = " . json_encode($produtos_ids) . ";
                    var usuario = " . json_encode($usuario) . ";
                    var endereco = $('#endereco').val();
                    var bairro = $('#bairro').val();
                    var numero = $('#numero').val();
                    var cidade = $('#cidade').val();
                    var estado = $('#estado').val();
                    var cep = $('#cep').val();
                    var complemento = $('#complemento').val();
                    var formapagamento = $('#formapagamento').val();
                    var qtds = [];
    
                    // Obtendo as quantidades dos produtos
                    produtos.forEach(function(id_produto) {
                        var qtd = $('#quantity_' + id_produto).val();
                        qtds.push(qtd);
                    });

                    var endereco = $('[name=\"endereco\"]').val().trim();
                    var bairro = $('[name=\"bairro\"]').val().trim();
                    var numero = $('[name=\"numero\"]').val().trim();
                    var cidade = $('[name=\"cidade\"]').val().trim();
                    var cep = $('[name=\"cep\"]').val().trim();
    
                    if (!formapagamento || qtds.includes('') || !endereco || !bairro || !numero || !cidade || !cep) {
                        $('.toast-body').text('Preencha todos os campos obrigatórios, incluindo o endereço e a forma de pagamento.');
                        $('.toast').toast('show');
                        return;
                    }
    
                    $.ajax({
                        url: '../model/add_pedido.php',
                        type: 'POST',
                        data: { 
                            produtos: produtos, 
                            usuario: usuario,
                            endereco: endereco,
                            bairro: bairro,
                            numero: numero,
                            cidade: cidade,
                            estado: estado,
                            cep: cep,
                            complemento: complemento,
                            qtd: qtds,
                            formapagamento: formapagamento,
                            carrinho: " . json_encode($produtos[0]->ID) . "
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                window.location.href = response.redirect;
                            } else {
                                alert('Erro ao finalizar pedido. Tente novamente.');
                            }
                        },
                        error: function() {
                            alert('Erro ao se comunicar com o servidor.');
                        }
                    });
                });
            });
        </script>";
        
        return $string;
    }
?>
