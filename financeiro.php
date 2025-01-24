<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Financeira</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="formulariopadrao.css">
    <style>
        .content {
            display: flex;
            flex-direction: row;
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
        }

        #cabecalho-main {
            display: flex;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-bottom-style: hidden;
            background-color: #44749D;
            color: #fff;
            padding: 1em;
            text-align: center;
            align-items: center;
        }

        #cabecalho-main .img_fun {
            width: 20%;
            margin: 2px;
            padding: 2px;
            border-radius: 50%;
        }

        #cabecalho-main .img {
            align-content: left;
            padding: 1% 1%;
            height: 92%;
            width: 16%;
            justify-content: center;
            cursor: pointer;
            margin-left: 7%;
        }

        #cabecalho-main h3 {
            margin: 0 10px;
            margin-left: auto;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: max-content;
            text-align: center;
            border: 1px solid black;
        }

        .card h3 {
            margin-bottom: 20px;
        }

        .card table {
            width: 100%;
            border-collapse: collapse;
        }

        .card table th,
        .card table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .card table th {
            background-color: #3a3f51;
        }

        .card button {
            background-color: #3a3f51;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #2a2e3a;
            /* Cor de hover mais escura */
            box-shadow: 0 0 8px rgba(58, 63, 81, 0.6);
            /* Sombra baseada na cor de fundo ajustada */
        }

        #piechart_3d {
            max-width: 600px;
            margin: 30px auto;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            border: 1px solid #575b6a;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
    <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php"; ?>
    <div class="container">
        <div id="balance" class="content">
            <?php
            $conexao = mysqli_connect("localhost", "root", "", "erp");

            $sql_consulta = "SELECT valor_total_venda FROM venda";
            $resultado = mysqli_query($conexao, $sql_consulta);

            $calculo_valor_total_bruto = 0; // Inicializa a variável de soma

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $valor_bruto = $linha["valor_total_venda"];
                    $calculo_valor_total_bruto += $valor_bruto; // Soma o valor atual ao total acumulado
                }
            }
            $calculo_valor_total_salario = 0;
            $calculo_valor_total_compra = 0;

            // Consulta para obter os salários dos funcionários
            $sql_consulta_salarios = "SELECT salario_fun FROM funcionario";
            $resultado_salarios = mysqli_query($conexao, $sql_consulta_salarios);

            if (mysqli_num_rows($resultado_salarios) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado_salarios)) {
                    $valor_salario = $linha["salario_fun"];
                    $calculo_valor_total_salario += $valor_salario; // Soma o valor atual ao total acumulado
                }
            }

            // Consulta para obter os valores de compra
            $sql_consulta_compras = "SELECT valor_com FROM compra";
            $resultado_compras = mysqli_query($conexao, $sql_consulta_compras);

            if (mysqli_num_rows($resultado_compras) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado_compras)) {
                    $valor_compra = $linha["valor_com"];
                    $calculo_valor_total_compra += $valor_compra; // Soma o valor atual ao total acumulado
                }
            }

            // Soma total de despesas
            $valor_total_despesas = $calculo_valor_total_compra + $calculo_valor_total_salario;
            $valor_liquido = $calculo_valor_total_bruto - $valor_total_despesas;

            ?>

            <div class="card">
                <h3>Balanço Financeiro</h3>
                <table>
                    <tr>
                        <th>Categoria</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>Receitas</td>
                        <td> R$ <?php echo $calculo_valor_total_bruto; ?></td>
                    </tr>
                    <tr>
                        <td>Despesas</td>
                        <td>R$ <?php echo $valor_total_despesas; ?> </td>
                    </tr>
                    <tr>
                        <td>Lucro</td>
                        <td>R$ <?php echo $valor_liquido; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="reports" class="content">
            <div class="card">
                <h3>Relatórios Mensais</h3>
                <button onclick="gerar_relatorio()">Gerar Relatório</button>
                <div id="report-output"></div>
            </div>
        </div>
        <div id="accounts-payable" class="content">
            <div class="card">
                <h3>Contas a Pagar</h3>
                <table>
                    <tr>
                        <th>Fornecedor</th>
                        <th>Valor</th>
                        <th>Data de Vencimento</th>
                    </tr>
                    <?php
                    $sql_consulta_fornecedor = "SELECT 
    c.valor_com AS valor_compra, 
    c.vencimento_com AS data_compra, 
    f.nome_for AS nome_fornecedor
FROM 
    compra c
INNER JOIN 
    fornecedores f ON c.fornecedores_cod_for = f.cod_for
    where status_com ='P';";
                    $resultado_relatorio = mysqli_query($conexao, $sql_consulta_fornecedor);

                    // Verifica se a consulta retornou algum resultado
                    if (mysqli_num_rows($resultado_relatorio) > 0) {
                        // Itera sobre cada linha do resultado
                        while ($row = mysqli_fetch_assoc($resultado_relatorio)) {
                            echo "<tr>";
                            echo "<td>" . $row['nome_fornecedor'] . "</td>";
                            echo "<td>R$ " . $row['valor_compra'] . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($row['data_compra'])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Se não houver resultados, exibe uma mensagem
                        echo "<tr><td colspan='3'>Nenhum resultado encontrado.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            // Carrega o pacote 'corechart' da Google Charts
            google.charts.load("current", {
                packages: ["corechart"]
            });

            // Função de callback (Fica sempre rodando) chamada quando o pacote é carregado
            google.charts.setOnLoadCallback(drawChart);

            // Função para desenhar o gráfico
            function drawChart() {
                // Cria um DataTable com os dados das categorias e valores
                var data = google.visualization.arrayToDataTable([
                    ['Categoria', 'Valor'],
                    ['Receitas', <?php echo $calculo_valor_total_bruto; ?>],
                    ['Salários', <?php echo $calculo_valor_total_salario; ?>],
                    ['Compras', <?php echo $calculo_valor_total_compra; ?>]
                ]);

                // Opções para o gráfico, como título, formato 3D, largura e altura
                var options = {
                    title: 'Receitas, Salários e Compras',
                    is3D: true,
                    width: '100%',
                    height: 400
                };

                // Cria uma instância do gráfico de Pizza e o desenha no elemento com ID 'piechart_3d'
                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart.draw(data, options);
            }

            // Função para gerar relatório (placeholder)
            function gerar_relatorio() {
                // Esta função pode ser implementada conforme necessário para gerar relatórios dinâmicos
                document.getElementById('report-output').innerHTML = 'Relatório gerado!';
            }
        </script>

    </div>
    <div id="piechart_3d" class="content"></div>
    <script>
        // Função assíncrona para gerar relatório
        async function gerar_relatorio() {
            // Realiza uma requisição usando fetch para 'gerar_relatorio.php'
            const response = await fetch('gerar_relatorio.php');

            // Aguarda a resposta da requisição e obtém o conteúdo como texto
            const data = await response.text();

            // Define o conteúdo obtido dentro do elemento HTML com o ID 'report-output'
            document.getElementById('report-output').innerHTML = data;
        }
    </script>


</body>

</html>