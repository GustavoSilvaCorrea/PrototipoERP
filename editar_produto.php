<?php
session_start()
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Produtos</title>
    <script src="https://kit.fontawesome.com/d090179400.js" crossorigin="anonymous"></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js'></script>
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

        #menu {
            width: 65px;
            height: 65px;
        }

        #valida {
            display: flex;
            flex-direction: row-reverse;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 10px;
            padding-bottom: 50px;
        }

        .form-container {
            width: 100%;
            max-width: 650px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        textarea,
        input[type="text"],
        input[type="number"],
        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #3a3f51;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2a2e3a;
            /* Cor de hover mais escura */
            box-shadow: 0 0 8px rgba(58, 63, 81, 0.6);
            /* Sombra baseada na cor de fundo ajustada */
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php" ?>
    <main>
        <div class="container">
            <div class="form-container">

                <form action="salva_editar_produto.php" method="POST">
                    <header style="text-align: center;">
                        <h2>Alterar produtos</h2>
                    </header>
                    <label for="nome_prod">Produto</label>
                    <select id="nome_prod" name="nome_prod" required>
                        <?php
                        $conectar = mysqli_connect("localhost", "root", "", "erp");

                        if (!$conectar) {
                            die("Conexão falhou: " . mysqli_connect_error());
                        }

                        $sql_consulta = "SELECT nome_prod FROM produto";
                        $resultado_consulta = mysqli_query($conectar, $sql_consulta);

                        if (mysqli_num_rows($resultado_consulta) > 0) {
                            while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
                                $nome_prod = $linha["nome_prod"];
                                echo "<option value='$nome_prod'>$nome_prod</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum produto encontrado</option>";
                        }

                        mysqli_close($conectar);
                        ?>
                    </select>
                    <label for="descricao_prod">Descrição</label>
                    <textarea id="descricao_prod" name="descricao_prod" rows="4"></textarea>

                    <label for="medida_prod">Medida</label>
                    <input type="text" id="medida_prod" name="medida_prod">

                    <label for="quantidade_est">Quantidade</label>
                    <input type="number" id="quantidade_est" name="quantidade_est">

                    <label for="estoque_min_prod">Estoque Mínimo</label>
                    <input type="number" id="estoque_minimo_est" name="estoque_minimo_est">

                    <label for="tipo_prod">Tipo</label>
                    <input type="text" id="tipo_prod" name="tipo_prod">

                    <label for="preco_prod">Preço</label>
                    <input type="number" id="preco_prod" name="preco_prod" step="0.01">

                    <label for="foto_prod">Foto do Produto</label>
                    <input type="file" id="foto_prod" name="foto_prod">

                    <input type="submit" value="Alterar">
                </form>
            </div>
        </div>

    </main>
    <div class="lateral-menu hidden" id="lateral-menu">
        <ul>
            <li><a href="estoque.php">Estoque</a></li>
            <li><a href="comprea.php">Compra de Produtos</a></li>
            <li><a href="cadastro_produtos.php">Cadastro de Produtos</a></li>
        </ul>
    </div>
</body>

</html>