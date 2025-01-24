<?php
session_start();
?>
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
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php"; ?>

    <main>
        <section class="report-input">
            <form action="salva_cadastro_relatorio.php" method="POST">
                <header style="text-align: center;">
                    <h2>Realizar relatórios</h2>
                </header>
                <label for="report-title">Título do Relatório:</label>
                <input type="text" name="titulo" required>

                <label for="report-type">Tipo do Relatório:</label>
                <input type="text" name="tipo" required>

                <label for="report-importance">Nível de Importância (1 a 5):</label>
                <select name="nivel" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <label for="report-content">Conteúdo do Relatório:</label>
                <textarea name="conteudo" rows="10" required style="margin-bottom: 10px;"></textarea>

                <input type="submit" value="Enviar Relatorio">
            </form>
        </section>
    </main>
</body>

</html>