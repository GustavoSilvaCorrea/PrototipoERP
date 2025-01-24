<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        /* Estilos da Página Inteira */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 25px;
            margin-bottom: 50px;
            flex-direction: column;
        }

        .produtos {
            border: 1px solid #ddd;
            width: 80%;
            max-width: 800px;
            margin-bottom: 50px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        #fotos {
            width: 50%;
            height: auto;
            border-radius: 8px;
            margin: 0 auto;
            display: block;
        }

        #nome {
            text-align: center;
            font-weight: 600;
            font-size: 24px;
            text-transform: uppercase;
            padding: 1em;
            background-color: #f0f0f0;
        }

        #sales-table {
            border-collapse: collapse;
            width: 100%;
            color: #800040;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .chart {
            height: 20px;
            background-color: #f44336;
            border-radius: 5px;
            margin: 5px 0;
            position: relative;
        }

        .chart::before {
            content: "";
            display: block;
            height: 100%;
            background-color: #000;
            border-radius: 5px;
        }

        .chart[style*="width:"]::before {
            width: calc(100% - 10px);
        }

        h5 {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
            color: #800040;
        }

        .details {
            text-align: left;
            padding: 1em;
            width: 20%;
            text-align: center;
        }

        .details p {
            margin: 0.5em 0;
            display: flex;
            align-items: center;
        }

        .details p i {
            margin-right: 8px;
            color: #333;
        }

        #valida {
            display: flex;
            flex-direction: row-reverse;
        }

        @media only screen and (max-width: 768px) {
            #toggle-menu {
                top: -2px;
                left: 7px;
            }
        }

        .produtos {
            width: 90%;
        }

        th,
        td {
            padding: 8px;
        }

        @media only screen and (min-width: 769px) {
            .produtos {
                width: 90%;
            }
        }

        #chart_div {
            max-width: 600px;
            margin: 30px auto;
            width: 100%;
            height: 400px; /* Adicionado para definir uma altura */
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
    <?php
    $conectar = mysqli_connect("localhost", "root", "", "erp");

    if (isset($_GET['nome'])) {
        $nome_prod = htmlspecialchars($_GET['nome']); // Escapar HTML para prevenir XSS
    } else {
        echo "Nenhum nome foi passado.";
        exit;
    }

    // Verificação de conexão
    if (!$conectar) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    $sql_consulta = "SELECT produto.*, estoque.*, SUM(estoque.quantidade_est) AS total_estoque
                     FROM produto 
                     INNER JOIN estoque ON produto.cod_prod = estoque.produto_cod_prod
                     WHERE produto.nome_prod LIKE ?
                     GROUP BY produto.cod_prod";
    $stmt = mysqli_prepare($conectar, $sql_consulta);
    mysqli_stmt_bind_param($stmt, "s", $nome_prod);
    mysqli_stmt_execute($stmt);
    $resultado_count = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado_count) > 0) {
        $linha = mysqli_fetch_assoc($resultado_count);
        $nome_prod = $linha["nome_prod"];
        $descricao = $linha["descricao_prod"];
        $medida = $linha["medida_prod"];
        $estoqueMin = $linha["estoque_minimo_est"];
        $tipo = $linha["tipo_prod"];
        $preco = $linha["preco_prod"];
        $foto_prod = $linha["foto_prod"];
        $total_estoque = $linha["total_estoque"];
    } else {
        echo "Erro ao buscar o produto.";
        exit;
    }

    // Consultar total de gastos (compras) do produto
    $sql_gastos = "SELECT SUM(c.valor_com) AS total_gastos
                   FROM compra c
                   INNER JOIN estoque e 
                   ON c.cod_com = e.compra_cod_com
                   WHERE e.produto_cod_prod = ?";
    $stmt_gastos = mysqli_prepare($conectar, $sql_gastos);
    mysqli_stmt_bind_param($stmt_gastos, "i", $linha['cod_prod']);
    mysqli_stmt_execute($stmt_gastos);
    $resultado_gastos = mysqli_stmt_get_result($stmt_gastos);

    if ($resultado_gastos && mysqli_num_rows($resultado_gastos) > 0) {
        $linha_gastos = mysqli_fetch_assoc($resultado_gastos);
        $total_gastos = $linha_gastos['total_gastos'];
    } else {
        // Em caso de erro ou se não houver compras para o produto
        $total_gastos = 0;
    }

    // Consultar total de lucros (vendas) do produto
    $sql_lucro = "SELECT SUM(venda.valor_total_venda) AS total_lucro
                  FROM venda
                  INNER JOIN estoque ON venda.estoque_cod_est = estoque.cod_est
                  WHERE estoque.produto_cod_prod = ?";
    $stmt_lucro = mysqli_prepare($conectar, $sql_lucro);
    mysqli_stmt_bind_param($stmt_lucro, "i", $linha['cod_prod']);
    mysqli_stmt_execute($stmt_lucro);
    $resultado_lucro = mysqli_stmt_get_result($stmt_lucro);

    if ($resultado_lucro && mysqli_num_rows($resultado_lucro) > 0) {
        $linha_lucro = mysqli_fetch_assoc($resultado_lucro);
        $total_lucro = $linha_lucro['total_lucro'];
    } else {
        // Em caso de erro ou se não houver vendas para o produto
        $total_lucro = 0;
    }

    mysqli_close($conectar);
    ?>

    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php" ?>

    <div class="container">
        <div class="produtos">
            <img id="fotos" src="<?php echo $foto_prod; ?>" alt="Foto do Produto">
            <h1 id="nome"><?php echo $nome_prod; ?></h1>
            <p><?php echo $descricao; ?></p>
            <table id="sales-table">
                <thead>
                    <tr>
                        <th>MEDIDA</th>
                        <th>TIPO</th>
                        <th>ESTOQUE MÍNIMO</th>
                        <th>ESTOQUE TOTAL</th>
                        <th>PREÇO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $medida; ?></td>
                        <td><?php echo $tipo; ?></td>
                        <td><?php echo $estoqueMin; ?></td>
                        <td><?php echo $total_estoque; ?></td>
                        <td>R$ <?php echo number_format($preco, 2, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>

            <div id="chart_div"></div>
        </div>
    </div>

    <script type="text/javascript">
        // Carrega o pacote 'corechart' da Google Charts
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Função de callback chamada quando o pacote é carregado
        google.charts.setOnLoadCallback(drawChart);

        // Função para desenhar o gráfico
        function drawChart() {
            // Cria um DataTable com os dados das categorias e seus valores, incluindo estilo
            var data = google.visualization.arrayToDataTable([
                ['Element', 'Valor', { role: 'style' }],
                ['Gastos', <?php echo $total_gastos; ?>, '#f44336'],
                ['Lucro', <?php echo $total_lucro; ?>, '#4caf50']
            ]);

            // Opções para o gráfico, como título, eixos horizontal e vertical, e estilo da legenda
            var options = {
                title: 'Comparação de Gastos e Lucro',
                hAxis: {
                    title: 'Categorias',
                    titleTextStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    title: 'Valor (R$)',
                    minValue: 0
                },
                legend: 'none'
            };

            // Cria uma instância do gráfico de coluna e o desenha no elemento com ID 'chart_div'
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

</body>

</html>
