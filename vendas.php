<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas</title>
    <link rel="icon" href="icon_path" type="image/icon type">
    <link rel="icon" href="tijolo.png" type="image/x-icon" />
    <link rel="stylesheet" href="formulariopadrao.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
    <style>
        .pesquisa {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
        }

        #filtros {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }

        .inputs {
            display: flex;
            width: 90%;

        }

        #search {
            flex: 1;
            text-align: center;
            width: 300px;
            font-weight: 800;
            font-size: 20px;
            border-radius: 50px;
            border: 5px solid !important;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2 !important;
            margin-right: 10px;
            margin-bottom: 0;
        }

        #date {
            background-color: #dcdcdc;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 600;
            border-radius: 50px;
            border: 5px solid !important;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2 !important;
            padding: 20px 20px;
            /* Ajustar o espaçamento interno */
            max-width: 300px;
            /* Limitar a largura máxima */
        }
        .card-title {
            text-align: center;
            font-size: 25px;
            font-weight: 600;
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 10px;
            color: #44749D;
        }

        #caixa-inventario {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .container {
            width: 90% !important;
            display: flex;
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .inventory-box {
            width: 20%;
            height: 300px;
            background-image: url(img/cart.png);
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 30px;
            margin: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
        }

        .inventory-box:hover {
            box-shadow: 10px 10px #44749D;
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php" ?>
    <div class="pesquisa">
        <div class="inputs">
            <input type="text" placeholder="Pesquise o funcionário" id="search" name="pesquisa">
            <header id="filtros">
                <p style='margin: 0;'>Data</p>
                <section>
                    <input type="date" id="date" name="pesquisa">
                </section>
            </header>
        </div>
    </div>
    <div class="container">
        <div id="caixa-inventario">
            <?php
            $conectar = mysqli_connect("localhost", "root", "", "erp");
            $sql_consulta = "SELECT * FROM venda";
            $resultado = mysqli_query($conectar, $sql_consulta);

            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $nome_cli = $linha["nome_cliente_venda"];
                    $produto = $linha["produto_venda"];
                    $data = $linha["data_venda"];
                    $quantidade = $linha["quantidade_venda"];
                    $valor = $linha["valor_total_venda"];
                    $desc = $linha["descricao_venda"];
                    $pagamento = $linha["forma_pagamento_venda"];
                    $cod = $linha["cod_venda"];
                    $fun = $linha["funcionario_cod_fun"];
                    echo '<div class="inventory-box" data-product-type="' . date('d/m/Y', strtotime($data)) . '">';
                    echo '<div class="card-title">'. $nome_cli . '</div>';
                    echo '<h4 style="text-align: center; margin-bottom: 5px; margin-top: 0;"> '. $valor .' R$</h4>';
                    echo '<textarea readonly style="resize: none;" cols="5" rows="5">'. $desc .'</textarea>';
                    echo '<label>' . 'Cliente: ' . $nome_cli . '</label><br>';
                    echo '<label>' . 'Produto: ' . $produto . '</label><br>';


                    echo '</div>';
                }
            } else {
                echo "Nenhuma venda encontrada.";
            }

            mysqli_close($conectar);
            ?>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchBar = document.getElementById('search');
        var dateInput = document.getElementById('date');
        var inventoryBoxes = document.querySelectorAll('.inventory-box');

        searchBar.addEventListener('input', function() {
            var inputValue = searchBar.value.toLowerCase().trim();

            inventoryBoxes.forEach(function(box) {
                var venda = box.querySelector('label').textContent.toLowerCase();
                box.style.display = venda.includes(inputValue) ? 'block' : 'none';
            });
        });

        dateInput.addEventListener('change', function() {
            var selectedDate = dateInput.value;
            if (!selectedDate) {
                inventoryBoxes.forEach(box => box.style.display = 'flex');
                return;
            }

            var formattedDate = selectedDate.split('-').reverse().join('/');

            inventoryBoxes.forEach(function(box) {
                var reportDate = box.getAttribute('data-product-type');
                box.style.display = (formattedDate === reportDate) ? 'block' : 'none';
            });
        });
    });
</script>

</html>