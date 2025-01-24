<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
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
            display: flex !important;
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

        main {
            display: flex;
            flex-grow: 1;
            padding: 2rem;
            gap: 2rem;
        }

        .report-input {
            display: grid !important;
            place-self: center !important;
        }

        section {
            flex: 1;
            max-width: 600px;
            background: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 1rem;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 3px;
        }

        input[type="text"],
        input[type="number"],
        input[type="submit"],
        select,
        textarea {
            width: 100%;
            padding: 0.5rem;
            padding: 8px;
            border-radius: 4px;
            box-sizing: border-box;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
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
        <h1>Relatórios</h1>
        <?php include "iconpage.php" ?>
        <h3><?php session_start();
            echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <?php
    $conectar = mysqli_connect("localhost", "root", "", "erp");

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM relatorio WHERE cod_rel=$id";
        $res = $conectar->query($sql);
        if ($res->num_rows > 0) {
            while ($dados = mysqli_fetch_assoc($res)) {
                $tipo_rel = $dados['tipo_rel'];
                $data_rel = $dados['data_rel'];
                $nivel_rel = $dados['nivel_rel'];
                $titulo_rel = $dados['titulo_rel'];
                $conteudo_rel = $dados['conteudo_rel'];
                $funcionario_cod_fun = $dados['funcionario_cod_fun'];
                $id = $dados['cod_rel'];
            }
            $sql = "SELECT nome_fun FROM funcionario WHERE cod_fun = $funcionario_cod_fun";
            $res_for = $conectar->query($sql);
            if ($res_for->num_rows > 0) {
                while ($linha = mysqli_fetch_assoc($res_for)) {
                    $nome_fun = $linha["nome_fun"];
                }
            } else {
                $nome_fun = "Nome do fornecedor não encontrado";
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

    <main>
        <section class="report-input">
            <form action="salva_editar_relatorio.php" method="POST">
                <header style="text-align: center;">
                    <h2>Editar relatório</h2>
                </header>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="report-title">Título do Relatório:</label>
                <input type="text" name="titulo" value="<?php echo $titulo_rel; ?>">

                <label for="report-type">Tipo do Relatório:</label>
                <input type="text" name="tipo" value="<?php echo $tipo_rel; ?>">

                <label for="report-importance">Nível de Importância (1 a 5):</label>
                <select name="nivel" >
                    <?php switch ($nivel_rel) {
                        case '1':
                            echo "<option value='1' selected>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            ";
                            break;
                        case '2':
                            echo "<option value='2' selected>2</option>
                            <option value='1'>1</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            ";
                            break;
                        case '3':
                            echo "<option value='3' selected>3</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            ";
                            break;
                        case '4':
                            echo "<option value='4' selected>4</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='5'>5</option>
                            ";
                            break;
                        case '5':
                            echo "<option value='5' selected>5</option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            
                            ";
                            break;
                    }?>
                </select>

                <label for="report-content">Conteúdo do Relatório:</label>
                <textarea name="conteudo" rows="10" style="margin-bottom: 10px;"><?php echo $conteudo_rel; ?></textarea>

                <input type="submit" value="Enviar Relatorio">
            </form>
        </section>
    </main>
</body>

</html>