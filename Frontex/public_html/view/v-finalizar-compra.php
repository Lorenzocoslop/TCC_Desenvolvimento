<?php

include_once realpath(__DIR__ . '/../../connection/connection.php');

$title = "Finalizar Compra";

include_once realpath(__DIR__ . '/../controllers/c-home.php');
include_once realpath(__DIR__ . '/../controllers/c-finalizar-compra.php');
include_once realpath(__DIR__ . '/../controllers/c-carrinho.php');

$string = "
    <main class='bg-tertiary'>
            <div class = 'row'>
            ";
            $string.="
                    <div class='accordion col col-md-7 mt-5 ms-3' id='accordionExample'>
                        <div class='accordion-item'>
                            <h2 class='accordion-header'>
                                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseprodutos' aria-expanded='true' aria-controls='collapseOne'>
                                    Produtos
                                </button>
                            </h2>
                            <div id='collapseprodutos' class='accordion-collapse collapse show' data-bs-parent='#accordionExample'>
                                <div class='accordion-body'>";
                                    $string .= listarProdutos();
                                $string .= "
                                </div>
                            </div>
                        </div>
                        <div class='accordion-item'>
                            <h2 class='accordion-header'>
                                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseendereco' aria-expanded='true' aria-controls='collapseOne'>
                                    Endere&ccedil;o
                                </button>
                            </h2>
                            <div id='collapseendereco' class='accordion-collapse collapse show' data-bs-parent='#accordionExample'>
                                <div class='accordion-body'>";
                                    $string .= gerarEnderecoForm();
                                $string .= "
                                </div>
                            </div>
                        </div>
                        <div class='accordion-item'>
                            <h2 class='accordion-header'>
                                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseformapagamento' aria-expanded='true' aria-controls='collapseOne'>
                                    Formas de Pagamento
                                </button>
                            </h2>
                            <div id='collapseformapagamento' class='accordion-collapse collapse show' data-bs-parent='#accordionExample'>
                                <div class='accordion-body'>";
                                    $string .= gerarFormaPagamentoForm();
                                $string .= "
                                </div>
                            </div>
                        </div>
                    </div>";
                        
                    $string .="
                <div class = 'col col-md-4 '>";
                $string.= totalPedido();
                $string.="
                </div>
            </div>";
                

            $string.="
        </div>
    <main>
    ";
    $string .= gerarAjax();
    $string.="
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