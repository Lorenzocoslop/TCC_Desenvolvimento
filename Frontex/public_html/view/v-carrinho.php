<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Carrinho";

include_once realpath(__DIR__ . '/../controllers/c-home.php');
include_once realpath(__DIR__ . '/../controllers/c-carrinho.php');


$string = "
    <main class = 'bg-secondary'>
        <div class = 'container'>
            <div class = 'row'>
                <div class = 'col col-md-8'>
                    <div class='bg-light m-5 border rounded'>
                        <h2 class = 'text-center border-bottom border-dark'>Meu Carrinho</h2>";
                        $carrinho = new Carrinho();
                        $dados = $carrinho->buscaPedidos();
                        $string .= Carrinho::listarProdutos($dados);
                    $string .="
                    </div>
                </div>
                <div class = 'col col-md-4 '>
                    <div class='bg-light m-5 rounded p-4 mx-4'>
                        <h2 class = 'text-center'>Resumo da compra</h2>
                            <div class = 'row p-4 text-center'>
                                <div class='col col-md-6'> 
                                    <h5>Produtos</h5>
                                </div>
                                <div class='col col-md-6'> 
                                    <h5><strong>R$ 22,20</strong></h5>
                                </div>
                            </div>
                            <div class = 'row p-4 text-center'>
                                <div class='col col-md-7'> 
                                    <a href='#'>Insira um cupom</a>
                                </div>
                            </div>
                            <div class = 'row p-4 text-center'>
                                <div class='col col-md-6'> 
                                    <h5>Preço Total</h5>
                                </div>
                                <div class='col col-md-6'> 
                                    <h5><strong>R$ 22,20</strong></h5>
                                </div>
                            </div>  
                    </div>
                </div>
            </div>
            <section class = 'row bg-light m-5 rounded p-4 mx-4'>
                <h2> Produtos Sugeridos </h2>
                ";
            $produtos = new Produtos();
            $dados = $produtos->buscaProdutos();
            $string .= Produtos::gerarCardProdutos($dados);
            $string.="
            </section>
        </div>
    <main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');
        const quantityInput = document.getElementById('quantity');

        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
        });

        $(document).ready(function() {
            $('.btn-excluir').on('click', function() {
                var produtoId = $(this).data('id');
                var itemId = '#itemProduto_' + produtoId; // Cria o seletor do ID da linha

                $.ajax({
                    url: '../model/excluir_produto_carrinho.php',
                    type: 'POST',
                    data: { ID_produto: produtoId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(itemId).remove(); // Remove a linha do produto excluído
                        } else {
                            alert('Erro ao excluir o produto. Tente novamente.');
                        }
                    },
                    error: function() {
                        alert('Erro ao se comunicar com o servidor.');
                    }
                });
            });
        });
    </script>

";


include_once realpath(__DIR__ . '/../templates/template.php'); ?>