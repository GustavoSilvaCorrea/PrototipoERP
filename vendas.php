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
            width: 25%;
        }

        #search {
            flex: 1;
            width: 300px;
            font-weight: 600;
            font-size: 20px;
            border-radius: 40px;
            border: 2px solid #dcdcdc;
            margin-right: 10px;
        }

        main {
            display: flex;
            justify-content: center;
        }

        #caixa-inventario {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .inventory-box {
            background-color: #333;
            width: auto;
            height: auto;
            padding: 25px;
            border: 3px solid #444;
            border-radius: 15px;
            margin: 10px;
            position: relative;
            color: white;
        }

        .inventory-box:hover {
            box-shadow: 10px 10px #44749D;
        }

        .inventory-box img {
            width: 100%;
            height: auto;
            border: 3px solid #444;
        }

        #link {
            display: flex;
            justify-content: center;
            text-decoration: none;
            margin: 10px 0;
            font-size: 16px;
            font-weight: 500;
            color: white;
        }

        #link:hover {
            text-shadow: 2px 2px #44749D;
        }

        #link p {
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
    <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php" ?>
    <div class="content">
        <div class="pesquisa">
            <div class="inputs">
                <input type="date" placeholder="Pesquise a venda" id="search" name="pesquisa">
                <script>
                    // Este script JavaScript se refere a barra de pesquisa
                    document.addEventListener('DOMContentLoaded', function() {

                        // Seleciona a div  com o ID 'search'
                        var searchBar = document.getElementById('search');

                        // Adiciona um evento de entrada a div 'searchBar'
                        searchBar.addEventListener('input', function() {

                            // Obtém o valor inserido no campo de pesquisa, convertido para minúsculas
                            var inputValue = searchBar.value.toLowerCase();

                            // Seleciona todos os elementos HTML com a classe 'inventory-box'
                            var inventoryBoxes = document.querySelectorAll('.inventory-box');

                            // Itera sobre todos os elementos selecionados com a classe 'inventory-box'
                            inventoryBoxes.forEach(function(box) {

                                // Seleciona o nome do funcionáro dentro do primeiro elemento <p> encontrado dentro de cada 'inventory-box', convertido para minúsculas
                                var venda = box.querySelector('p').textContent.toLowerCase();

                                // Verifica se o texto da venda inclui o valor inserido no campo de pesquisa
                                if (venda.includes(inputValue)) {
                                    box.style.display = 'block'; // Se sim, mostra o elemento
                                } else {
                                    box.style.display = 'none'; // Se não, esconde o elemento
                                }
                            });
                        });
                    });
                </script>

                <header id="filtros">
                    <p>Filtro</p>
                    <section>
                        <select name="nome_fun" id="nome_fun">
                    </section>
                </header>
                <?php
                $conectar = mysqli_connect("localhost", "root", "", "erp");
                if (!$conectar) {
                    die("Falha na conexão: " . mysqli_connect_error());
                }

                $sql_consulta = "SELECT DISTINCT v.funcionario_cod_fun, f.nome_fun FROM venda v INNER JOIN funcionario f ON v.funcionario_cod_fun = f.cod_fun";

                $resultado_consulta = mysqli_query($conectar, $sql_consulta);

                if (mysqli_num_rows($resultado_consulta) > 0) {
                    while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
                        echo "<option value='" . $linha['funcionario_cod_fun'] . "'>" . $linha['nome_fun'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum funcionário encontrado</option>";
                }
                ?>
                </select>
                <script>
                    // Este script JavaScript se refere aos filtros
                    document.addEventListener('DOMContentLoaded', function() {

                        // Seleciona o elemento HTML com o ID 'nome_fun'
                        var tipoProdSelect = document.getElementById('nome_fun');

                        // Seleciona todos os elementos HTML com a classe 'inventory-box'
                        var inventoryBoxes = document.querySelectorAll('.inventory-box');

                        // Adiciona um evento de mudança ao elemento 'tipoProdSelect'
                        tipoProdSelect.addEventListener('change', function() {

                            // Obtém o valor selecionado no elemento 'tipoProdSelect'
                            var selectedValue = tipoProdSelect.value;

                            // Itera sobre todos os elementos selecionados com a classe 'inventory-box'
                            inventoryBoxes.forEach(function(box) {

                                // Obtém o valor do atributo 'data-product-type' de cada 'inventory-box'
                                var productType = box.getAttribute('data-product-type');

                                // Verifica se o valor selecionado é 'Todos' ou se corresponde ao 'data-product-type' da 'inventory-box'
                                if (selectedValue === 'Todos' || productType === selectedValue) {
                                    box.style.display = 'block'; // Se sim, mostra o elemento
                                } else {
                                    box.style.display = 'none'; // Se não, esconde o elemento
                                }
                            });
                        });
                    });
                </script>

            </div>
        </div>
        <main>
            <div id="caixa-inventario">
                <?php
                $sql_consulta = "SELECT * FROM venda";
                $resultado = mysqli_query($conectar, $sql_consulta);

                if (mysqli_num_rows($resultado) > 0) {
                    while ($linha = mysqli_fetch_assoc($resultado)) {
                        $nome_cli = $linha["nome_cliente_venda"];
                        $produto = $linha["produto_venda"];
                        $data = $linha["data_venda"];
                        $quantidade = $linha["quantidade_venda"];
                        $valor = $linha["valor_total_venda"];
                        $pagamento = $linha["forma_pagamento_venda"];
                        $cod = $linha["cod_venda"];
                        $fun = $linha["funcionario_cod_fun"];
                        echo '<div class="inventory-box" data-product-type="' . $fun . '">';
                        echo '<a href="detalhe_venda.php?cod=' . urlencode($cod) . '" id="link"><p>' . $data . '</p></a>';
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
        </main>
    </div>
</body>

</html>