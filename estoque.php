<?php
session_start()
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
    <link rel="stylesheet" type="text/css" href="css/estoque_css.css" />
</head>

<body>
    <!-- Cabeçalho -->
    <header id="cabecalho-main">
        <!-- Imagem de icone -->
        <?php include "iconpage.php" ?>
        <!-- Nome do funcionário -->
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <!-- Navbar -->
    <?php include "navbar.php"; ?>




    <div class="content">
        <div class="pesquisa">
            <div class="inputs">
                <script>
                    // Este script JavaScript é se refere a barra de pesquisa
                    document.addEventListener('DOMContentLoaded', function() {

                        // Seleciona o a div com o ID 'search'
                        var searchBar = document.getElementById('search');

                        // Adiciona um evento de entrada a div 'searchBar'
                        searchBar.addEventListener('input', function() {

                            // Obtém o valor inserido no campo de pesquisa, convertido para minúsculas
                            var inputValue = searchBar.value.toLowerCase();

                            // Seleciona todos os elementos HTML com a classe 'inventory-box'
                            var inventoryBoxes = document.querySelectorAll('.inventory-box');

                            // Itera sobre todos os elementos selecionados com a classe 'inventory-box'
                            inventoryBoxes.forEach(function(box) {

                                // Seleciona o elemento <p> dentro de cada 'inventory-box' e obtém seu nome convertido de texto, convertido para minúsculas
                                var productName = box.querySelector('p').textContent.toLowerCase();

                                // Verifica se o nome do produto inclui o valor inserido no campo de pesquisa
                                if (productName.includes(inputValue)) {
                                    box.style.display = 'block'; // Se sim, mostra o elemento
                                } else {
                                    box.style.display = 'none'; // Se não, esconde o elemento
                                }
                            });
                        });
                    });
                </script>
                <input type="text" placeholder="Pesquise o produto" id="search" name="pesquisa">
                <header id="filtros">
                    <script>
                        // Este script JavaScript se refere ao filtro
                        document.addEventListener('DOMContentLoaded', function() {

                            // Seleciona o elemento HTML com o ID 'tipo_prod'
                            var tipoProdSelect = document.getElementById('tipo_prod');

                            // Seleciona todos os elementos HTML com a classe 'inventory-box'
                            var inventoryBoxes = document.querySelectorAll('.inventory-box');

                            // Adiciona um evento de mudança ao elemento 'tipo_prod'
                            tipoProdSelect.addEventListener('change', function() {

                                // Obtém o valor selecionado do elemento 'tipo_prod'
                                var selectedValue = tipoProdSelect.value;

                                // Itera sobre todos os elementos selecionados com a classe 'inventory-box'
                                inventoryBoxes.forEach(function(box) {

                                    // Obtém o atributo 'data-product-type' de cada elemento 'inventory-box'
                                    var productType = box.getAttribute('data-product-type');

                                    // Verifica se o valor selecionado é 'Todos' ou se o tipo do produto é igual ao valor selecionado
                                    if (selectedValue === 'Todos' || productType === selectedValue) {
                                        box.style.display = 'block'; // Mostra o elemento
                                    } else {
                                        box.style.display = 'none'; // Esconde o elemento
                                    }
                                });
                            });
                        });
                    </script>
                    <p>Tipos</p>
                    <select name="tipo_prod" id="tipo_prod">
                        <?php
                        $conectar = mysqli_connect("localhost", "root", "", "erp");

                        $conectar = mysqli_connect("localhost", "root", "", "erp");


                        $sql_consulta = "SELECT DISTINCT p.tipo_prod FROM produto p JOIN estoque e ON p.cod_prod = e.produto_cod_prod";
                        $resultado_consulta = mysqli_query($conectar, $sql_consulta);

                        // Verificando se a consulta retornou resultados
                        if (mysqli_num_rows($resultado_consulta) > 0) {
                            // Loop para criar opções do select
                            while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
                                $tipo_prod = $linha["tipo_prod"];
                                echo "<option value='$tipo_prod'>$tipo_prod</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum produto encontrado</option>";
                        }

                        ?>
                    </select>
                </header>
            </div>
        </div>
    </div>
    
    <!-- Main -->
    <main>
        <div id="caixa-inventario">
            <?php
            $conectar = mysqli_connect("localhost", "root", "", "erp");


            $sql_consulta = "SELECT p.cod_prod, p.nome_prod, p.tipo_prod, p.foto_prod, e.estoque_minimo_est, e.quantidade_est 
                FROM produto p
                JOIN estoque e ON p.cod_prod = e.produto_cod_prod";


            // Executar a consulta
            $resultado = mysqli_query($conectar, $sql_consulta);

            // Verificar se a consulta retornou algum resultado
            if (mysqli_num_rows($resultado) > 0) {
                // Iterar sobre os resultados
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    $nome = $linha["nome_prod"];
                    $foto = $linha["foto_prod"];
                    $min = $linha["estoque_minimo_est"];
                    $quantidade = $linha["quantidade_est"];
                    $tipo = $linha["tipo_prod"];

                    // Função que gera a porcentagem
                    if (!function_exists('percentual')) {
                        function percentual($quantidade, $min)
                        {

                            // Calcula a diferença percentual
                            $diferenca_percentual = (($quantidade * 100) / (10 * $min));


                            // Classifica a diferença percentual em 10%, 25%, 50%, 75% ou 100%
                            if ($diferenca_percentual <= 10) {
                                return 10;
                            } elseif ($diferenca_percentual <= 25) {
                                return 25;
                            } elseif ($diferenca_percentual <= 50) {
                                return 50;
                            } elseif ($diferenca_percentual <= 75) {
                                return 75;
                            } else {
                                return 100;
                            }
                        }
                    }

                    $porcentagem = percentual($quantidade, $min);
                    echo '<div class="inventory-box" data-product-type="' . $tipo . '">';
                    echo '<img src="' . $foto . '" alt="' . $nome . '">';
                    echo '<a href="produtos.php?nome=' . urlencode($nome) . '" id="link"><p>' . $nome . '</p></a>';

                    switch ($porcentagem) {
                        case ($porcentagem <= 10):

                            echo '<div class="quantity-bar" style="animation-name: barra0-10;">';
                            echo '<p id="porcento">10%</p>';
                            echo '<p style="color: red; font-size: 17px;" ></p>';
                            echo '</div>';
                            break;
                        case ($porcentagem <= 25):
                            echo '<div class="quantity-bar" style="animation-name: barra11-25;">';
                            echo '<p style="color: orangered; font-size: 17px;"></p>';
                            echo '</div>';
                            break;
                        case ($porcentagem <= 50):
                            echo '<div class="quantity-bar" style="animation-name: barra26-50;">';
                            echo '<p style="color: yellow; font-size: 17px;"></p>';
                            echo '</div>';
                            break;
                        case ($porcentagem <= 75):
                            echo '<div class="quantity-bar" style="animation-name: barra51-75;">';
                            echo '<p style="color: lime; font-size: 17px;"></p>';
                            echo '</div>';
                            break;
                        case ($porcentagem <= 100):
                            echo '<div class="quantity-bar" style="animation-name: barra76-100;">';
                            echo '<p style="color: green; font-size: 17px;"></p>';
                            echo '</div>';
                            break;
                        default:
                    }
                    echo '</div>';
                }
            } else {
                echo "Nenhum produto encontrado.";
            }
            ?>

        </div>
    </main>
    </div>
</body>
</html>