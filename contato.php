<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contatos</title>
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
        }

        #cabecalho-main h3 {
            margin: 0 10px;
            margin-left: auto;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
            margin-top: 20px;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        .contact-info {
            text-align: center;
            margin-top: 20px;
        }

        .contact-info a {
            color: #44749D;
            text-decoration: none;
            font-weight: bold;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .back-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .back-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #44749D;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button a:hover {
            background-color: #365f7d;
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h1>Contatos</h1>
    </header>
    <main>
        <div class="container">
            <h2>Entre em Contato Conosco</h2>
            <p>Se você tiver qualquer dúvida ou precisar de mais informações, por favor, entre em contato conosco
                através do e-mail abaixo:</p>
            <div class="contact-info">
                <p>E-mail: <a href="mailto:sisteamerpgrup@gmail.com">sisteamerpgrup@gmail.com</a></p>
            </div>

            <div class="back-button">
                <a href="index.php">Voltar</a>
            </div>
        </div>
    </main>
</body>

</html>