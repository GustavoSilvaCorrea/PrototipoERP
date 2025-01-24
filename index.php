<!DOCTYPE html>
<html lang="pt-br">

<?php
session_start(); // Inicia a sessão



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conectar = mysqli_connect("localhost", "root", "", "erp");
    $login = mysqli_real_escape_string($conectar, $_POST["login"]);
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM funcionario WHERE login_fun = ? AND (status_fun = 'a' OR status_fun = 'A')";
    $stmt = mysqli_prepare($conectar, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $senha_hash = $user["senha_fun"];
       
        if (password_verify($senha, $senha_hash)) {
            $_SESSION["cod_fun"] = $user["cod_fun"];
            $_SESSION["nome_fun"] = $user["nome_fun"];
            $_SESSION["status_fun"] = $user["status_fun"];
            $_SESSION["funcao_fun"] = $user["funcao_fun"];

            echo "<script>alert('Logado com sucesso!');</script>";
            header("Location: main_page.php");
            exit();
        } else {
            echo "<script>
            alert('Senha Incorreta! Digite Novamente!');
            location.href = 'index.php';
            </script>";
        }
    } else {
        echo "<script>
        alert('Login Incorreto! Digite Novamente!');
        location.href = 'index.php';
        </script>";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/background_rotativo.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            background-color: #333; /* Fundo escuro */
            color: #fff; /* Texto branco */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        main {
            width: 100%;
            max-width: 400px;
            padding: 2rem;

        }
        #background{
            background-size: cover;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .formulario_login {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #44749D; /* Azul */
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra */
        }

        .login-titulo {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .login-text {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            align-self: flex-start;
            color: #fff;
        }

        .login-input {
            width: 100%;
            height: 40px;
            font-size: 1rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 5px;
            background: #e0e0e0; /* Fundo dos inputs */
            color: #333;
            padding: 0.5rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra interna */
        }

        .login-input:focus {
            outline: none;
            border: 2px solid #365f7d; /* Borda ao focar */
        }

        .login-button {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            background: #365f7d; /* Azul escuro */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra */
        }

        .login-button:hover {
            background: #2a4d64; /* Azul mais escuro */
            transform: scale(1.05); /* Aumento ao passar o mouse */
        }

        #container-rodape {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .rodape {
            padding: 10px 15px;
            text-decoration: none;
            background-color: #44749D; /* Azul */
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Sombra */
        }

        .rodape:hover {
            background: #365f7d; /* Azul mais escuro */
            transform: scale(1.05); /* Aumento ao passar o mouse */
        }

        @media (min-width: 768px) {
            main {
                padding: 3rem;
            }

            .formulario_login {
                padding: 3rem;
            }

            .rodape {
                font-size: 1.25rem;
            }
        }
    </style>
</head>


<body id="background">
    <main>
        <div class="container">
            <form class="formulario_login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h1 class="login-titulo">Login</h1>
                <label for="login" class="login-text">Usuário</label>
                <input class="login-input" type="text" name="login" id="login" placeholder="Digite seu login">
                <label for="senha" class="login-text">Senha</label>
                <input class="login-input" type="password" name="senha" id="senha" placeholder="Digite sua senha">
                <button type="submit" class="login-button">Enviar</button>
            </form>
        </div>
        <footer id="container-rodape">
            <a href="termos.php" class="rodape">Termos</a>
            <a href="contato.php" class="rodape">Contato</a>
            <a href="privacidade.php" class="rodape">Privacidade</a>
        </footer>
    </main>
</body>


</html>
