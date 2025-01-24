<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="formulariopadrao.css">
    <style>
        /* Estilo da tabela com classe */
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

        .tabela-permissoes {
            width: 300px;
            margin: 10px 0;
            font-size: 14px;
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .tabela-permissoes tr {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s ease;
        }

        .tabela-permissoes tr:hover {
            background-color: #f1f1f1;
        }

        .tabela-permissoes td {
            padding: 10px 12px;
            border-right: 1px solid #ddd;
        }

        .tabela-permissoes td:last-child {
            border-right: none;
        }

        .tabela-permissoes p {
            margin: 0;
            display: flex;
            align-items: center;
        }

        .tabela-permissoes input[type="checkbox"] {
            margin-right: 5px;
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
            <form action="salva_cadastro_funcionario.php" method="POST">
                <header style="text-align: center;">
                    <h2>Cadastrar funcionário</h2>
                </header>
                <label>Nome:</label>
                <p><input type="text" name="nome" required></p>
                <label>CPF:</label>
                <p><input type="text" name="cpf" pattern="[0-9]{3}[0-9]{3}[0-9]{3}[0-9]{2}" required></p>
                <label>Email</label>
                <p><input type="email" name="email" required></p>
                <label>Endereço:</label>
                <p><input type="text" name="endereco" required></p>
                <label>Data de Nascimento:</label>
                <p><input type="date" name="data" required></p>
                <label>Telefone/Celular:</label>
                <p><input type="number"  pattern="[0-9]{2}[9]{1}[0-9]{4}[0-9]{4}" name="telefone"></p>
                <?php if ($_SESSION["funcao_fun"] == "Aministrador") { ?>
                    <label>Função</label>
                    <p><input type="text" name="funcao" required></p>
                <?php
                } else {
                ?><input type="hidden" name="funcao" value=""><?php
                                                                }
                                                                    ?>
                <label>Salário</label>
                <p><input type="number" name="salario" required></p>
                <p><label>Permissões</label required></p>
                <div class="permissoes">
                    <table class="tabela-permissoes">
                        <tr>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="relatorios"> Relatórios</p>
                            </td>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="financeiro"> Financeiro</p>
                            </td>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="vendas"> Vendas</p>
                            </td>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="compra"> Compras</p>
                            </td>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="estoque"> Estoque</p>
                            </td>
                            <td>
                                <p><input type="checkbox" name="permissoes[]" value="funcionarios"> Funcionário</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <label>Login:</label>
                <p><input type="text" name="login" required></p>
                <p><input type="hidden" name="status" value="A" required></p>
                <label>Senha:</label>
                <p><input type="password" name="senha" required></p>
                <label>Feedback:</label>
                <p><textarea name="feedback"></textarea></p>
                <label>Foto:</label>
                <p><input type="file" name="foto" required></p>
                <input type="submit" value="Cadastrar">
            </form>
        </div>
    </div>
    <div class="container">
        <div class="table-container">
            <h1>Listar Usuário</h1>
            <?php
            $conectar = mysqli_connect("localhost", "root", "", "erp");
            $sql_pesquisa = "SELECT * FROM funcionario";
            $res = mysqli_query($conectar, $sql_pesquisa);

            ?>
            <table class="table text-white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Data de Admissão</th>
                        <th scope="col">Data de Nascimento</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Função</th>
                        <th scope="col">Permissões</th>
                        <th scope="col">Salário</th>
                        <th scope="col">Feedback</th>
                        <th scope="col">Status</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($dado = mysqli_fetch_assoc($res)) {
                        $foto = $dado["foto_fun"];
                        echo "<tr>";
                        echo "<td>" . $dado['cod_fun'] . "</td>";
                        echo "<td>" . $dado['nome_fun'] . "</td>";
                        echo "<td>" . $dado['endereco_fun'] . "</td>";
                        echo "<td>" . $dado['data_adimissao_fun'] . "</td>";
                        echo "<td>" . $dado['nascimento_fun'] . "</td>";
                        echo "<td>" . $dado['tel_fun'] . "</td>";
                        echo "<td>" . $dado['funcao_fun'] . "</td>";
                        echo "<td style='overflow-wrap: break-word; max-width: 175px;'>" . $dado['permissao_fun'] . "</td>";
                        echo "<td>" . $dado['salario_fun'] . "</td>";
                        echo "<td>" . $dado['feedback_fun'] . "</td>";
                        echo "<td>" . $dado['status_fun'] . "</td>";
                        echo "<td> <img style='width: 50px; height: 50px;' src='" . $foto . "' alt='" . $foto . "'> </td>";
                        echo "<td>
                                <a class='btn btn-sm btn-primary' href='editar_funcionario.php?id=$dado[cod_fun]' title='Editar'>
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