<?php
include_once realpath(__DIR__ . '/../model/config.empresa.php');
include_once realpath(__DIR__ . '/../controllers/c-carrinho.php');
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

    function gerarEnderecoForm($endereco = '', $bairro = '', $numero = '', $cidade ='', $estado = '', $cep = '', $id = '') {
    
        $string = "
        <form method='post' enctype='multipart/form-data'> 
            <input type='hidden' name='id' value='$id'>  
            <div class='conteiner'>
                <div class='row'>"; 
                    $string.= Form::InputText([
                        'size' => 6,
                        'name' => 'endereco',
                        'label' => 'Rua',
                        'value' => $endereco,
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'bairro',
                        'label' => 'Bairro',
                        'value' => $bairro,
                    ]);

                    $string.= Form::InputText([
                        'size' => 2,
                        'name' => 'numero',
                        'label' => 'N&uacute;mero',
                        'value' => $numero,
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'cidade',
                        'label' => 'Cidade',
                        'value' => $cidade,
                    ]);

                    $estados = [];
                    $estados = Utils::listarEstados();

                    $string.= Form::select([
                        'size' => 4,
                        'name' => 'estado',
                        'options' => $estados,
                        'label' => 'Estado',
                        'value' => $estado,
                    ]);

                    $string.= Form::InputText([
                        'size' => 4,
                        'name' => 'cep',
                        'id' => 'cep',
                        'label' => 'CEP',
                        'class' => 'cep',
                        'value' => $cep,
                    ]);
                    $string .=
                        "
                </div>
            </div>
        </form>
    ";
        return $string;
    }

    function gerarFormaPagamentoForm($formapagamento = '', $id = ''){
        $string = "
        <form method='post' enctype='multipart/form-data'> 
            <input type='hidden' name='id' value='$id'>  
            <div class='conteiner'>
                <div class='row'>"; 
                    $string.= Form::select([
                        'size' => 12,
                        'name' => 'formapagamento',
                        'label' => 'Selecione uma forma de pagamento',
                        'options' => [
                            "1" => "Cartão de Crédito"
                        ],
                        'value' => $formapagamento,
                    ]);
                    $string .=
                        "
                </div>
            </div>
        </form>
    ";
        return $string;
    }
    
?>
