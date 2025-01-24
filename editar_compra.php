<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionario</title>
    <style>
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

        #cabecalho-main h3 {
            margin: 0 10px;
            margin-left: auto;
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

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        input[type="password"],
        input[type="file"],
        input[type="submit"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #080808;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        @media only screen and (max-width: 768px) {
            .form-container {
                width: 95%;
            }

            input[type="text"],
            input[type="email"],
            input[type="date"],
            input[type="number"],
            input[type="password"],
            input[type="file"],
            input[type="submit"] {
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <?php
    $conectar = mysqli_connect("localhost", "root", "", "erp");

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM compra WHERE cod_com=$id";
        $res = $conectar->query($sql);
        if ($res->num_rows > 0) {
            while ($dados = mysqli_fetch_assoc($res)) {
                $data = $dados['data_com'];
                $data_vencimento = $dados['vencimento_com'];
                $valor = $dados['valor_com'];
                $item = $dados['item_com'];
                $qtd = $dados['qtd_com'];
                $obs = $dados['observacao_com'];
                $status = $dados['status_com'];
                $fornecedor = $dados['fornecedores_cod_for'];
            }
            $sql = "SELECT nome_for FROM fornecedores WHERE cod_for = $fornecedor";
            $res_for = $conectar->query($sql);
            if ($res_for->num_rows > 0) {
                while ($linha = mysqli_fetch_assoc($res_for)) {
                    $nome_for = $linha["nome_for"];
                }
            } else {
                $nome_for = "Nome do fornecedor não encontrado";
            }
        } else {
            header('Location: ../main_page.php');
            exit;
        }
    } else {
        header('Location: main_page.php');
        exit;
    }
    ?>
    <header id="cabecalho-main">
    <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="form-container">
            <form action="salva_editar_compra.php" method="POST">
                <header style="text-align: center;">
                    <h2>Alterar compra</h2>
                </header>
                <input type="hidden" name="id" value="<?php echo $id; ?>" required>
                
                <label>Data da Compra</label>
                <input type="date" name="data" value="<?php echo $data; ?>" required>

                <?php if ($status == 'P') {
                echo '<label>Data de vencimento da compra</label>
                <input type="date" name="data-vencimento" value='. $data_vencimento .' required>';
                } ?>

                <label>Valor</label>
                <input type="number" name="valor" value="<?php echo $valor; ?>" required>

                <label>Item</label>
                <input type="text" name="item" value="<?php echo $item; ?>" required>

                <label>Quantidade</label>
                <input type="number" name="qtd" value="<?php echo $qtd; ?>" required>

                <label>Observações</label>
                <input type="text" name="obs" value="<?php echo $obs; ?>">

                <label>Status</label>
                <input type="text" name="status" value="<?php echo $status; ?>" required>

                <label>Fornecedores</label>
                <input type="text" name="fornecedores" value="<?php echo $nome_for; ?>" readonly>

                <input type="submit" value="Editar">
            </form>
        </div>
    </div>

</body>

</html>