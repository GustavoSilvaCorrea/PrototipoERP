<?php
session_start();

// Faz a conexão com o banco de dados
$conexao = mysqli_connect("localhost", "root", "", "erp");

// Verifica se a conexão foi estabelecida corretamente
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Consulta SQL para obter o total de vendas por mês
$resultado_vendas = mysqli_query($conexao, "SELECT SUM(valor_total_venda) AS total_vendas, MONTH(data_venda) AS mes, YEAR(data_venda) AS ano FROM venda GROUP BY ano, mes");
if (!$resultado_vendas) {
    die("Erro ao executar a consulta de vendas: " . mysqli_error($conexao));
}

// Consulta SQL para obter o total de compras por mês
$resultado_compras = mysqli_query($conexao, "SELECT SUM(valor_com) AS total_compras, MONTH(data_com) AS mes, YEAR(data_com) AS ano FROM compra WHERE status_com = 'A' GROUP BY ano, mes");
if (!$resultado_compras) {
    die("Erro ao executar a consulta de compras: " . mysqli_error($conexao));
}

// Consulta SQL para obter as compras pendentes
$resultado_pendentes = mysqli_query($conexao, "SELECT 
    c.cod_com, c.data_com, c.valor_com, c.item_com, c.qtd_com, c.observacao_com, c.status_com, c.vencimento_com, f.nome_for
    FROM compra c
    INNER JOIN fornecedores f ON c.fornecedores_cod_for = f.cod_for
    WHERE c.status_com = 'P'");
if (!$resultado_pendentes) {
    die("Erro ao executar a consulta de compras pendentes: " . mysqli_error($conexao));
}

// Consulta SQL para obter os relatórios de avisos
$resultado_avisos = mysqli_query($conexao, "SELECT r.cod_rel,
    r.titulo_rel,r.tipo_rel, r.conteudo_rel, r.data_rel, f.nome_fun 
    FROM relatorio r
    INNER JOIN funcionario f ON r.funcionario_cod_fun = f.cod_fun
    WHERE r.tipo_rel = 'aviso'");
if (!$resultado_avisos) {
    die("Erro ao executar a consulta de avisos: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formulariopadrao.css">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            display: block !important;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            box-sizing: border-box;
            border: 2px solid #44749D;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            flex-direction: row;
            justify-content: flex-start;
        }

        .section-title {
            margin: 20px 0;
            font-size: 25px;
            font-weight: 600;
            color: #44749D;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;

        }

        .card {
            background-image: url(img/cart.png);
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
        }

        .card-title {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 10px;
            color: #44749D;
        }

        .card-content {
            text-align: justify;
            font-size: 16px;
            line-height: 1.5;

        }

        .centro {
            display: flex;
            justify-content: center;
        }

        .detalhes {
            width: 50%;
            margin-left: 24%;
            border-radius: 25px;
            text-align: center;
            font-size: 18px;
            margin-top: 10px;
            text-align: center;
            text-decoration: none !important;
            /* Remove underline */
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            background-color: #44749D;
        }

        .detalhes:hover {
            background: #365f7d;
            /* Azul mais escuro */
            transform: scale(1.05);
            /* Aumento ao passar o mouse */
        }

        .icon-button {
            all: unset;
            /* Removes all default styles (padding, margin, border, background, etc.) */
            cursor: pointer;
            /* Adds pointer cursor to indicate interactivity */
            display: inline-block;
            /* Ensures proper layout for the image */
        }

        .icon-button img {
            display: block;
            /* Removes inline spacing */
            width: 50px;
            /* Adjust size as needed */
            height: 50px;
            /* Keep a consistent aspect ratio */
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        // Carrega o pacote de gráficos do google
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawCharts);

        // Função que chama as outras funções que "desenham" os gráficos
        function drawCharts() {
            drawBarChart();
            drawLineChart();
        }

        // Função que "desenha" o grafico de barras
        function drawBarChart() {
            // Joga os dados do banco de dados na tabela gráfica
            var data = google.visualization.arrayToDataTable([
                ['Mês', 'Vendas', 'Compras'],
                <?php
                while ($row_venda = mysqli_fetch_array($resultado_vendas)) {
                    $mes = $row_venda['mes'];
                    $ano = $row_venda['ano'];
                    $total_vendas = $row_venda['total_vendas'];
                    $total_compras = 0;

                    mysqli_data_seek($resultado_compras, 0);
                    while ($row_compra = mysqli_fetch_array($resultado_compras)) {
                        if ($row_compra['mes'] == $mes && $row_compra['ano'] == $ano) {
                            $total_compras = $row_compra['total_compras'];
                            break;
                        }
                    }
                    // Define que o mês tenha apenas dois dígitos
                    echo "['" . $ano . "-" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "', " . $total_vendas . ", " . $total_compras . "],";
                }
                ?>
            ]);

            // Opções do gráfico
            var options = {
                title: 'Performance Financeira',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            // Ele pega a div que irá mostrar o grafico de barras e joga as informações nela
            var chart = new google.visualization.BarChart(document.getElementById('chart_div-bars'));
            chart.draw(data, options);
        }

        // Função que "desenha" o gráfico de linhas
        function drawLineChart() {
            // Joga os dados do banco de dados na tabela gráfica
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Mês');
            data.addColumn('number', 'Vendas');
            data.addColumn('number', 'Compras');

            <?php
            // Verifica os dados das linha 0 das respectivas variáveis
            mysqli_data_seek($resultado_vendas, 0);
            mysqli_data_seek($resultado_compras, 0);

            $vendas = [];
            while ($row = mysqli_fetch_assoc($resultado_vendas)) {
                // Define que o mês tenha apenas dois dígitos
                $mes_ano = $row['ano'] . '-' . str_pad($row['mes'], 2, '0', STR_PAD_LEFT);
                $vendas[$mes_ano] = $row['total_vendas'];
            }

            $compras = [];
            while ($row = mysqli_fetch_assoc($resultado_compras)) {
                // Define que o mês tenha apenas dois dígitos
                $mes_ano = $row['ano'] . '-' . str_pad($row['mes'], 2, '0', STR_PAD_LEFT);
                $compras[$mes_ano] = $row['total_compras'];
            }

            // Pega as vendas que tenha o mesmo mês do $mes_ano
            foreach ($vendas as $mes_ano => $venda) {
                $compra = isset($compras[$mes_ano]) ? $compras[$mes_ano] : 0;
                // Se tiver ele adiciona na tabela gráfica
                echo "data.addRow(['" . $mes_ano . "', " . $venda . ", " . $compra . "]);";
            }
            ?>

            // Opções do gráfico
            var options = {
                title: 'Vendas e Compras ao Longo do Tempo',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                },
                hAxis: {
                    title: 'Mês'
                },
                vAxis: {
                    title: 'Valor'
                }
            };
            // Ele pega a div que irá mostrar o grafico de linhas e joga as informações nela
            var chart = new google.visualization.LineChart(document.getElementById('chart_div-lines'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php"; ?>
    <div class="container">

        <!-- Compras Pendentes -->
        <h3 class="section-title">Compras Pendentes</h3>
        <div class="cards-container">


            <div class="centro">
                <!-- Left Arrow -->
                <button class="icon-button">
                    <img src="img/next.png" alt="arrow_prev" style="transform: scaleX(-1);">
                </button>

                <?php
                if (mysqli_num_rows($resultado_pendentes) > 0) {
                    $counter = 0; // Initialize counter

                    while ($row = mysqli_fetch_assoc($resultado_pendentes)) {
                        // Display the card
                        echo "<div class='card'>
                <div class='card-title'>" . $row['nome_for'] . "</div>
                <div class='card-content'>
                    <strong>Produto:</strong> " . $row['item_com'] . "<br>
                    <strong>Preço:</strong> R$" . $row['valor_com'] . "<br>
                    <strong>Quantidade:</strong> " . $row['qtd_com'] . "<br>
                    <strong>Observação:</strong> " . $row['observacao_com'] . "<br>
                    <strong>Data da Compra:</strong> " . $row['data_com'] . "<br>
                    <strong>Data de Validade:</strong> " . $row['vencimento_com'] . "<br>
                </div>
                <p class='detalhes'>
                    <a style='text-decoration: none; color: white;' href='editar_compra.php?id=$row[cod_com]' title='Editar'>Pagamento</a>
                </p>
            </div>";

                        $counter++; // Increment the counter

                        // Stop after 3 cards
                        if ($counter >= 3) {
                            break; // Exit the loop
                        }
                    }
                } else {
                    echo "<p>Nenhuma compra pendente.</p>";
                }
                ?>

                <button class="icon-button">
                    <img src="img/next.png" alt="arrow_prev">
                </button>
            </div>
        </div>




        <h3 class="section-title">Relatórios Importantes</h3>
        <div class="centro">
            <!-- Left Arrow -->
            <button class="icon-button">
                <img src="img/next.png" alt="arrow_prev" style="transform: scaleX(-1);">
            </button>

            <?php
            if (mysqli_num_rows($resultado_avisos) > 0) {
                $counter = 0; // Initialize counter

                while ($row = mysqli_fetch_assoc($resultado_avisos)) {
                    echo "<div class='card' style='background-image: url(img/news.png);' id='card_" . $row['cod_rel'] . "'>
                    <div class='card-title'>" . $row['titulo_rel'] . "</div>
                    <div class='card-content'>
                        <strong>Data:</strong> " . $row['data_rel'] . "<br>
                        <strong>Tipo:</strong> " . $row['tipo_rel'] . "<br>
                        <strong>Funcionário:</strong> " . $row['nome_fun'] . "
                    </div>
                    <p class='detalhes'><a style='text-decoration: none; color: white;' href='relatorios.php' class='ver-conteudo' data-id='" . $row['cod_rel'] . "'>Detalhes</a></p>
                  </div>";
                    
                }
            } else {
                echo "<p>Nenhum aviso encontrado.</p>";
            }
            ?>

            <!-- Right Arrow -->
            <button class="icon-button">
                <img src="img/next.png" alt="arrow_prev">
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Função para ocultar os cards previamente ocultados ao carregar a página
                function ocultarCardsOcultos() {
                    const cardsOcultos = JSON.parse(localStorage.getItem('cardsOcultos')) || [];

                    cardsOcultos.forEach(cardId => {
                        const card = document.getElementById('card_' + cardId);
                        if (card) {
                            card.style.display = 'none';
                        }
                    });
                }

                // Captura todos os links com classe 'ver-conteudo'
                const linksVerConteudo = document.querySelectorAll('.ver-conteudo');

                // Adiciona um evento de clique para cada link
                linksVerConteudo.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault(); // Previne o comportamento padrão do link

                        // Obtém o ID do card associado ao link clicado
                        const cardId = this.getAttribute('data-id');

                        // Oculta o card correspondente
                        const card = document.getElementById('card_' + cardId);
                        if (card) {
                            card.style.display = 'none';

                            // Salva o ID do card ocultado no localStorage
                            let cardsOcultos = JSON.parse(localStorage.getItem('cardsOcultos')) || [];
                            if (!cardsOcultos.includes(cardId)) {
                                cardsOcultos.push(cardId);
                                localStorage.setItem('cardsOcultos', JSON.stringify(cardsOcultos));
                            }

                            // Redireciona para a URL do link após ocultar o card
                            window.location.href = this.href;
                        }
                    });
                });

                // Ao carregar a página, oculta os cards previamente ocultados
                ocultarCardsOcultos();
            });
        </script>
    </div>
    <!-- Relatórios de Gráficos -->
    <div class="container-charts">
        <!-- Div que armazena o gráfico de barras -->
        <div id="chart_div-bars" style="width: 100%; height: 400px;"></div>
    </div>
    <div class="container-charts">
        <!-- Div que armazena o gráfico de linhas -->
        <div id="chart_div-lines" style="width: 100%; height: 400px;"></div>
    </div>
</body>

</html>