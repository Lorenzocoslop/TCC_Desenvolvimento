<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Carrinho";

include_once realpath(__DIR__ . '/../controllers/c-home.php');
include_once realpath(__DIR__ . '/../controllers/c-carrinho.php');

$carrinho = new Carrinho();
$dados = $carrinho->buscaPedidos();
$string = "
    <main class='bg-tertiary'>
        <div class = 'container'>
            <div class = 'row'>
            ";
            if(count($dados) == 0){
                $string.="
                <div class = 'col col-md-12'>
                    <div class='bg-light m-5 border rounded'>
                        <h2 class = 'text-center border-bottom border-dark'>Meu Carrinho</h2>";
                        $dados = $carrinho->buscaPedidos();
                        $string .= Carrinho::listarProdutos($dados);
                    $string .="
                    </div>
                </div>";        
            } else{
            $string.="
                <div class = 'col col-md-8'>
                    <div class='bg-light m-5 border rounded'>
                        <h2 class = 'text-center border-bottom border-dark'>Meu Carrinho</h2>";
                        $dados = $carrinho->buscaPedidos();
                        $string .= Carrinho::listarProdutos($dados);
                    $string .="
                    </div>
                </div>
                <div class = 'col col-md-4 '>";
                $string.= Carrinho::gerarTotalProdutos($dados);
                $string.="
                </div>
            </div>";
            }
                
            $string.="<section class = 'row bg-light m-5 rounded p-4 mx-4'>
                <h2> Produtos Sugeridos </h2>
                ";
            $produtos = new Produtos();
            $dados = $produtos->buscaProdutos();
            $string .= Produtos::gerarCardProdutos($dados);
            $string.="
            </section>
        </div>
    <main>
    <script src='../../js/ajax_compras.js'></script>        
    <script>
        $(document).ready(function() {
            function atualizarTotais() {
                $.ajax({
                    url: '../model/att_totais.php',
                    type: 'POST',
                    data: { 
                        tipo: 'atualizar_totais'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#totalsemcupom').html(response.totais.totalsemcupom);
                            $('#totalcomcupom').html(response.totais.totalcomcupom);
                        } else {
                            alert('Erro ao atualizar os totais. Tente novamente.');
                        }
                    },
                    error: function() {
                        alert('Erro ao se comunicar com o servidor.');
                    }
                });
            }

            function atualizarNotificacoes() {
                $.ajax({
                    url: '../model/att_notificacoes.php',
                    type: 'POST',
                    data: { 
                        tipo: 'atualizar_notificacoes'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#notificacoes').html(response.notificacoes.notificacoes);
                        } else {
                            alert('Erro ao atualizar as notificações. Tente novamente.');
                        }
                    },
                    error: function() {
                        alert('Erro ao se comunicar com o servidor.');
                    }
                });
            }

            function ajustarEstadoBotoes(produtoId) {
                var itemId = '#quantity_' + produtoId;
                var decreaseButtonId = '#decrease_' + produtoId;
                var currentValue = parseInt($(itemId).val(), 10);

                if (currentValue <= 1) {
                    $(decreaseButtonId).attr('disabled', 'disabled');
                } else {
                    $(decreaseButtonId).removeAttr('disabled');
                }
            }

            // Função para aumentar a quantidade
            $('.btn-quantity-increase').on('click', function() {
                var produtoId = $(this).data('id');
                var itemId = '#quantity_' + produtoId;
                var qtd = parseInt($(itemId).val(), 10);

                $.ajax({
                    url: '../model/alt_qtd_prod.php',
                    type: 'POST',
                    data: { 
                        ID_produto: produtoId, 
                        tipo: 'aumentar',
                        qtd: qtd
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $(itemId).val(qtd + 1);
                            ajustarEstadoBotoes(produtoId);
                            atualizarTotais();
                        } else {
                            alert('Erro ao alterar a quantidade. Tente novamente.');
                        }
                    },
                    error: function() {
                        alert('Erro ao se comunicar com o servidor.');
                    }
                });
            });

            $('.btn-quantity-decrease').on('click', function() {
                var produtoId = $(this).data('id');
                var itemId = '#quantity_' + produtoId;
                var qtd = parseInt($(itemId).val(), 10);

                if (qtd > 1) {
                    $.ajax({
                        url: '../model/alt_qtd_prod.php',
                        type: 'POST',
                        data: { 
                            ID_produto: produtoId, 
                            tipo: 'reduzir',
                            qtd: qtd
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $(itemId).val(qtd - 1);
                                ajustarEstadoBotoes(produtoId);
                                atualizarTotais();
                            } else {
                                alert('Erro ao alterar a quantidade. Tente novamente.');
                            }
                        },
                        error: function() {
                            alert('Erro ao se comunicar com o servidor.');
                        }
                    });
                }
            });
            
            $('.btn-quantity-decrease').each(function() {
                var produtoId = $(this).data('id');
                ajustarEstadoBotoes(produtoId);
            });

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
                            $(itemId).remove();
                            atualizarTotais();
                            atualizarNotificacoes();
                            if ($('[id^=";
                            $string.="itemProduto_";
                            $string.="]').length === 0) {
                                location.reload();
                            }
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
    </script>";


include_once realpath(__DIR__ . '/../templates/template.php'); ?>