<?php
session_start();
?>
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
        $sql = "SELECT * FROM fornecedores WHERE cod_for=$id";
        $res = $conectar->query($sql);
        if ($res->num_rows > 0) {
            while ($dados = mysqli_fetch_assoc($res)) {
                $nome_for = $dados['nome_for'];
                $cnpj = $dados['cnpj_cpf_for'];
                $endereco = $dados['endereco_for'];
                $email = $dados['email_for'];
                $tel = $dados['tel_for'];
            }
        } else {
            header('Location: ../main_page.php');
        }
    } else {
        header('Location: main_page.php');
    }
    ?>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h1>Edição de Funcionario</h1>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="form-container">
            <form action="salva_editar_fornecedor.php" method="POST">
                <header style="text-align: center;">
                    <h2>Alterar fornecedores</h2>
                </header>
                <input type="hidden" name="id" value="<?php echo $id; ?>" required>
                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo $nome_for; ?>" required>

                <label>Cnpj/Cpf</label>
                <input type="text" name="cnpj" value="<?php echo $cnpj; ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>

                <label>Endereço</label>
                <input type="text" name="endereco" value="<?php echo $endereco; ?>" required>

                <label>Telefone/Celular</label>
                <input type="number" name="telefone" value="<?php echo $tel; ?>">

                <input type="submit" value="Editar">
            </form>
        </div>
    </div>

</body>

</html>