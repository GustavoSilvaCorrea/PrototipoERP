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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
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
            max-width: 850px;
            margin: 20px auto;
            padding: 20px;
        }

        .produtos {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            padding: 20px;
        }

        #sales-table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background-color: #44749D;
            text-align: center !important;
            color: #fff;
            text-transform: uppercase;
        }

        td.details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        td.details p {
            margin: 0;
            display: flex;
            align-items: center;
            font-size: 1.1em;
        }

        td.details p i {
            margin-right: 10px;
            color: #44749D;
        }

        #nome {
            background-color: #44749D;
            text-align: center;
            font-weight: bold;
            font-size: 1.5em;
            padding: 10px 0;
        }

        @media only screen and (max-width: 768px) {

            th,
            td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>

    <?php
    $conectar = mysqli_connect("localhost", "root", "", "erp");

    if (isset($_GET['cod'])) {
        $cod_v = htmlspecialchars($_GET['cod']); // Usando htmlspecialchars para prevenir XSS
    } else {
        echo "Nenhum valor foi passado.";
        exit();
    }

    $sql_consulta_vend = "SELECT * FROM venda WHERE cod_venda LIKE ?";
    $stmt = mysqli_prepare($conectar, $sql_consulta_vend);
    mysqli_stmt_bind_param($stmt, 's', $cod_v);
    mysqli_stmt_execute($stmt);
    $resultado_count = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado_count) > 0) {
        $linha_v = mysqli_fetch_assoc($resultado_count);
        $nome_venda = $linha_v["nome_cliente_venda"];
        $data = $linha_v["data_venda"];
        $qnt = $linha_v["quantidade_venda"];
        $vlt = $linha_v["valor_total_venda"];
        $prod = $linha_v["produto_venda"];
        $pag = $linha_v["forma_pagamento_venda"];
        $fun = $linha_v["funcionario_cod_fun"];
    } else {
        echo "Erro ao encontrar venda.";
        exit();
    }

    $per = "SELECT nome_fun FROM funcionario WHERE cod_fun LIKE ?";
    $stmt = mysqli_prepare($conectar, $per);
    mysqli_stmt_bind_param($stmt, 's', $fun);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $linha_f = mysqli_fetch_assoc($resultado);
    $nome_f = $linha_f["nome_fun"];
    ?>

    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php" ?>

    <div class="container">
        <div class="produtos">
            <table id="sales-table">
                <thead>
                    <tr>
                        <th id="nome" colspan="2"><?php echo $nome_venda; ?></th>
                    </tr>
                    <tr>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="details">
                            <p><i class="fas fa-box"></i> Produto: <?php echo $prod; ?></p>
                            <p><i class="fas fa-sort-numeric-up"></i> Quantidade: <?php echo $qnt; ?></p>
                            <p><i class="fas fa-calendar-alt"></i> Data/Hora: <?php echo $data; ?></p>
                            <p><i class="fas fa-credit-card"></i> Forma de Pagamento: <?php echo $pag; ?></p>
                            <p><i class="fas fa-user"></i> Funcionário: <?php echo $nome_f; ?></p>
                            <p><i class="fas fa-wallet"></i> Valor da Venda: R$ <?php echo $vlt; ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>