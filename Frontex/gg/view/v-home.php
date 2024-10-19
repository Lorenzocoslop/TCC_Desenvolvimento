<?php
include_once realpath(__DIR__ . '/../controllers/c-home.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dataFim = '';
$dataInicio = '';
$status_quantities = [];
$status_labels = [];
$title = "GG Relatórios";

// Código para gerar o HTML e JavaScript para gráficos
$string = "
    <style>
    #info-total-pedidos {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
        margin: 10px 0;
        font-family: Arial, sans-serif;
    }

    h5 {
        margin-bottom: 10px; /* Para dar espaço entre o texto e o número */
        font-size: 1rem; /* Tamanho do texto descritivo */
    }

    h3 {
        font-size: 2rem; /* Tamanho do valor numérico */
    }

    #info-media-pedidos {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
        margin: 10px 0;
        font-family: Arial, sans-serif;
    }

    h5 {
        margin-bottom: 10px; /* Para dar espaço entre o texto e o número */
        font-size: 1rem; /* Tamanho do texto descritivo */
    }

    h3 {
        font-size: 2rem; /* Tamanho do valor numérico */
    }

    #info-recebimento-pedidos {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
        margin: 10px 0;
        font-family: Arial, sans-serif;
    }

    h5 {
        margin-bottom: 10px; /* Para dar espaço entre o texto e o número */
        font-size: 1rem; /* Tamanho do texto descritivo */
    }

    h3 {
        font-size: 2rem; /* Tamanho do valor numérico */
    }

    </style>
    <div class='container'>
        <h2 class='text-center'>Relatório de Pedidos</h2>
        <label for='periodo'>Período:</label>
        <select id='periodo' onchange='updateChart()'>
            <option value='semana'>Últimos 7 dias</option>
            <option value='mes'>Últimos 30 dias</option>
            <option value='ano'>Último ano</option>
            <option value='personalizado'>Personalizado</option>
        </select>

        <div id='data-personalizada' class='mt-1' style='display: none;'>
            <label for='data_inicio'>Data Início:</label>
            <input type='date' id='data_inicio'>
            <label for='data_fim'>Data Fim:</label>
            <input type='date' id='data_fim'>
            <button onclick='updateChartPersonalizado()'>Aplicar</button>
        </div>

        <div class='row mt-3'>
            <div id='info-total-pedidos' class='col-md-2 bg-secondary mx-3 p-3 rounded'>
                <h5 class='text-white'>Total de Pedidos</h5>
                <h3 class='text-white text-center' id='total-pedidos'>0</h3>
            </div>
            <div id='info-media-pedidos' class='col-md-2 bg-secondary mx-3 p-3 rounded'>
                <h5 class='text-white'>Média de Pedidos por dia</h5>
                <h3 class='text-white text-center' id='media-pedidos'>0</h3>
            </div>
            <div id='info-recebimento-pedidos' class='col-md-3 bg-secondary mx-3 p-3 rounded'>
                <h5 class='text-white'>Total Recebido do Período</h5>
                <h3 class='text-white text-center' id='recebimento-pedidos'>R$ 0,00</h3>
            </div>
        </div>

        <div id='chart'></div>
        <h2 class='text-center'>Status dos Pedidos</h2>
        <div id='pie-chart' style='margin-top: 30px;'></div>
    </div>

    <script>
        var options = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [],
            xaxis: {
                categories: []
            }
        };

        var chart = new ApexCharts(document.querySelector('#chart'), options);
        chart.render();

        var pieOptions = {
            chart: {
                type: 'pie',
                height: 350
            },
            series: " . json_encode($status_quantities) . ",
            labels: " . json_encode($status_labels) . "
        };

        var pieChart = new ApexCharts(document.querySelector('#pie-chart'), pieOptions);
        pieChart.render();

        function updateChart() {
            var periodo = document.getElementById('periodo').value;

            if (periodo === 'personalizado') {
                document.getElementById('data-personalizada').style.display = 'block';
                return;
            } else {
                document.getElementById('data-personalizada').style.display = 'none';
            }

            fetch('../scripts/atualizar_data_grafico.php?periodo=' + periodo)
                .then(response => response.json())
                .then(data => {
                    // Atualizar gráfico de barras
                    chart.updateSeries([{
                        name: 'Pedidos',
                        data: data.quantidade
                    }]);

                    chart.updateOptions({
                        xaxis: {
                            categories: data.categorias
                        }
                    });

                    // Atualizar total de pedidos, média por dia e total recebido
                    document.getElementById('total-pedidos').innerText = data.total_pedidos;
                    document.getElementById('media-pedidos').innerText = data.media_pedidos;
                    document.getElementById('recebimento-pedidos').innerText = 'R$ ' + data.total_recebido;

                    // Atualizar gráfico de pizza
                    pieChart.updateSeries(data.status_quantities); 
                    pieChart.updateOptions({
                        labels: data.status_labels
                    });
                });
        }

        function updateChartPersonalizado() {
            var dataInicio = document.getElementById('data_inicio').value;
            var dataFim = document.getElementById('data_fim').value;

            fetch('../scripts/atualizar_data_grafico.php?periodo=personalizado&data_inicio=' + dataInicio + '&data_fim=' + dataFim)
                .then(response => response.json())
                .then(data => {
                    // Atualizar gráfico de barras
                    chart.updateSeries([{
                        name: 'Pedidos',
                        data: data.quantidade
                    }]);

                    chart.updateOptions({
                        xaxis: {
                            categories: data.categorias
                        }
                    });

                    // Atualizar total de pedidos, média por dia e total recebido
                    document.getElementById('total-pedidos').innerText = data.total_pedidos;
                    document.getElementById('media-pedidos').innerText = data.media_pedidos;
                    document.getElementById('recebimento-pedidos').innerText = 'R$ ' + data.total_recebido;

                    // Atualizar gráfico de pizza
                    pieChart.updateSeries(data.status_quantities); 
                    pieChart.updateOptions({
                        labels: data.status_labels
                    });
                });
        }

        // Chamar a função inicial
        updateChart();
    </script>
";

include_once realpath(__DIR__ . '/../templates/template.php'); 
?>
