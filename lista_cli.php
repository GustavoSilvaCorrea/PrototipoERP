<?php session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");
if (!$conexao) {
    die("Connection
    failed: " . mysqli_connect_error());
}

// Verificar se o formulário de atualização foi enviado
if (isset($_POST['atualizar_cliente'])) {
    $cod_cli = $_POST['cod_cli'];
    $nome_cli = $_POST['nome_cli'];
    $tel_cli = $_POST['tel_cli'];
    $endereco_cli = $_POST['endereco_cli'];
    $descricao_cli = $_POST['descricao_cli'];
    $email_cli = $_POST['email_cli'];

    $sql_atualizar = " UPDATE cliente SET nome_cli='$nome_cli' , tel_cli='$tel_cli' , endereco_cli='$endereco_cli' ,
    descricao_cli='$descricao_cli',email_cli='$email_cli' WHERE cod_cli='$cod_cli' ";

    if (mysqli_query($conexao, $sql_atualizar)) {
        echo " <script>alert('Cliente atualizado com sucesso!');</script>";
    } else {
        echo "
    <script>alert('Erro ao atualizar cliente: " . mysqli_error($conexao) . "');</script>";
    }
}

// Consultar todos os clientes
$sql_clientes = "SELECT cod_cli, cpf_cli, nome_cli, tel_cli, endereco_cli,descricao_cli, email_cli FROM cliente";
$resultado_clientes = mysqli_query($conexao, $sql_clientes);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Clientes</title>
    <link rel="stylesheet" href="formulariopadrao.css">
    <style>
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

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 90%;
            padding: 5px;
            text-align: center;
            margin-left: 15px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #3a3f51;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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

    <?php include "navbar.php"; ?>
    <table class="table-container" style="width: 1500px; margin-left: 8px;">
        <thead>
            <tr>
                <th>CPF</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Email</th>
                <th>Descricao</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cliente = mysqli_fetch_assoc($resultado_clientes)) { ?>
                <tr>
                    <form method="POST">
                        <td><?php echo htmlspecialchars($cliente['cpf_cli']); ?></td>
                        <td>
                            <input type="hidden" name="cod_cli" value="<?php echo htmlspecialchars($cliente['cod_cli']); ?>">
                            <input type="text" name="nome_cli" value="<?php echo htmlspecialchars($cliente['nome_cli']); ?>" required>
                        </td>
                        <td><input type="tel" pattern="[0-9]{2}[9]{1}[0-9]{4}[0-9]{4}"  title="O número deve conter os caraceteres correto." name="tel_cli" value="<?php echo htmlspecialchars($cliente['tel_cli']); ?>">
                        </td>
                        <td><input type="text" name="endereco_cli" value="<?php echo htmlspecialchars($cliente['endereco_cli']); ?>"></td>
                        <td><input type="email" name="email_cli" value="<?php echo htmlspecialchars($cliente['email_cli']); ?>"></td>
                        <td><input type="text" name="descricao_cli" value="<?php echo htmlspecialchars($cliente['descricao_cli']); ?>"></td>
                        <td><input type="submit" name="atualizar_cliente" value="Atualizar"></td>
                    </form>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>