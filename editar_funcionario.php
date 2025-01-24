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
        #cabecalho-main .img_fun{
            width: 20%;
            margin: 2px;
            padding: 2px;
            border-radius: 50%;
        }
        #cabecalho-main .img{
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

        /* Estilo da tabela com classe */
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
        $sql = "SELECT * FROM funcionario WHERE cod_fun=$id";
        $res = $conectar->query($sql);
        if ($res->num_rows > 0) {
            while ($dados = mysqli_fetch_assoc($res)) {
                $nome_fun = $dados['nome_fun'];
                $email = $dados['email_fun'];
                $endereco = $dados['endereco_fun'];
                $data_nasc = $dados['nascimento_fun'];
                $telefone = $dados['tel_fun'];
                $funcao = $dados['funcao_fun'];
                $permissao = $dados['permissao_fun'];
                $login = $dados['login_fun'];
                $salario = $dados['salario_fun'];
                $feed = $dados['feedback_fun'];
                $status = $dados['status_fun'];
                $permissoes = explode(',', $permissao);
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
        <h3><?php echo $_SESSION["nome_fun"];?><?php include "valida_login.php";?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="form-container">
            <form action="salva_editar_funcionario.php" method="POST">
                <header style="text-align: center;">
                    <h2>Alterar funcionário</h2>
                </header>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo $nome_fun; ?>">

                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">

                <label>Endereço</label>
                <input type="text" name="endereco" value="<?php echo $endereco; ?>">

                <label>Data de Nascimento</label>
                <input type="date" name="data" value="<?php echo $data_nasc; ?>">

                <label>Telefone/Celular</label>
                <input type="number" name="telefone" value="<?php echo $telefone; ?>">
                
                <?php if ($_SESSION["funcao_fun"] == "Aministrador"){?>
                <label>Função</label>
                <p><input type="text" name="funcao" value="<?php echo $funcao; ?>"></p>
                <?php
                }else{
                    ?><input type="hidden" name="funcao" value=""><?php
                }
                ?>
                <label>Permissões</label>
                <table class="tabela-permissoes">
                    <tr>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="relatorios" <?php if (in_array('relatorios', $permissoes)) { echo 'checked'; } ?>> Relatórios</p>
                        </td>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="financeiro" <?php if (in_array('financeiro', $permissoes)) { echo 'checked'; } ?>> Financeiro</p>
                        </td>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="vendas" <?php if (in_array('vendas', $permissoes)) { echo 'checked'; } ?>> Vendas</p>
                        </td>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="compra" <?php if (in_array('compra', $permissoes)) { echo 'checked'; } ?>>Compras</p>
                        </td>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="estoque" <?php if (in_array('estoque', $permissoes)) { echo 'checked'; } ?>> Estoque</p>
                        </td>
                        <td>
                            <p><input type="checkbox" name="permissoes[]" value="funcionarios" <?php if (in_array('funcionarios', $permissoes)) { echo 'checked'; } ?>> Funcionário</p>
                        </td>
                    </tr>
                </table>

                <label>Login</label>
                <input type="text" name="login" value="<?php echo $login; ?>">

                <label>Senha</label>
                <input type="password" name="senha" >

                <label>Status</label>
                <input type="text" name="status" value="<?php echo $status ?>">

                <label>Salario</label>
                <input type="text" name="salario" value="<?php echo $salario ?>">

                <label>Feedback</label>
                <input type="text" name="feed" value="<?php echo $feed ?>">

                <label>Foto </label>
                <input type="file" name="foto">

                <input type="submit" value="Editar">
            </form>
        </div>
    </div>

</body>

</html>