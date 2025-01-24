<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

        .table-container {
            width: 100%;
            background-color: #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            background-color: #C6D4E1;
        }

        th {
            background-color: #44749D;
            font-weight: bold;
        }

        .btn {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            background-color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
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
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="form-container">
            <form action="salva_cadastro_fornecedores.php" method="POST">
                <header style="text-align: center;">
                    <h2>Cadastrar fornecedor</h2>
                </header>
                <label>Nome:</label>
                <p><input type="text" name="nome" required></p>
                <label>Cnpj:</label>
                <p><input type="text" pattern="[0-9]{2}[0-9]{3}[0-9]{3}[0-9]{4}[0-9]{2}" name="cnpj" required></p>
                <label>Endereço:</label>
                <p><input type="text" name="endereco" required></p>
                <label>Email</label>
                <p><input type="email" name="email" required></p>
                <label>Telefone/Celular:</label>
                <p><input type="tel" pattern="[0-9]{2}[9]{1}[0-9]{4}[0-9]{4}"  title="O número deve conter os caraceteres correto" name="tel"></p>
                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <h1>Listar fornecedores</h1>
            <?php

            $conectar = mysqli_connect("localhost", "root", "", "erp");
            $sql_pesquisa = "SELECT * FROM fornecedores";
            $res = mysqli_query($conectar, $sql_pesquisa);
            ?>
            <table class="table text-white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Cnpj/Cpf</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($dado = mysqli_fetch_assoc($res)) {
                        echo "<tr>";
                        echo "<td>" . $dado['cod_for'] . "</td>";
                        echo "<td>" . $dado['nome_for'] . "</td>";
                        echo "<td>" . $dado['cnpj_cpf_for'] . "</td>";
                        echo "<td>" . $dado['endereco_for'] . "</td>";
                        echo "<td>" . $dado['email_for'] . "</td>";
                        echo "<td>" . $dado['tel_for'] . "</td>";
                        echo "<td>
                                <a class='btn btn-sm btn-primary' href='editar_for.php?id=$dado[cod_for]' title='Editar'>
                                    <i class='fas fa-pencil-alt'></i>
                                </a>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>