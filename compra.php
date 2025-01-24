<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="formulariopadrao.css">
    <title>Compras</title>
</head>

<body>
    <header id="cabecalho-main">
    <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"];?><?php include "valida_login.php";?></h3>
    </header>

    <?php include "navbar.php"; ?>

    <div class="container">
        <div class="form-container">
            <h1>Pedido de Compra</h1>
            <form id="main-form" method="post" action="">
                <label for="fornecedor">Fornecedor:</label>
                <select id="fornecedor" name="fornecedor" required onchange="this.form.submit()">
                    <option value="">Selecione um fornecedor</option>
                    <?php
                    $conectar = mysqli_connect("localhost", "root", "", "erp");

                    if (!$conectar) {
                        die("Conexão falhou: " . mysqli_connect_error());
                    }

                    $sql_consulta = "SELECT nome_for FROM fornecedores";
                    $resultado_consulta = mysqli_query($conectar, $sql_consulta);

                    if (mysqli_num_rows($resultado_consulta) > 0) {
                        while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
                            $nome_for = $linha["nome_for"];
                            $selected = (isset($_POST['fornecedor']) && $_POST['fornecedor'] == $nome_for) ? 'selected' : '';
                            echo "<option value='$nome_for' $selected>$nome_for</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum fornecedor encontrado</option>";
                    }
                    ?>
                </select>
            </form>

            <?php
            $email = "";
            if (isset($_POST['fornecedor']) && $_POST['fornecedor'] != "") {
                $nome = $_POST['fornecedor'];
                $sql_consulta = "SELECT cod_for, email_for FROM fornecedores WHERE nome_for = '$nome'";
                $resultado_consulta = mysqli_query($conectar, $sql_consulta);

                if (mysqli_num_rows($resultado_consulta) > 0) {
                    while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
                        $email = $linha["email_for"];
                        $cod_for = $linha["cod_for"];
                    }
                }
            }
            ?>

            <form id="order-form" method="post" action="salva_cadastro_compra.php">
                <input type="hidden" id="email" name="email" value="<?php echo $email; ?>">
                <input type="hidden" id="cod_for" name="cod_for" value="<?php echo $cod_for; ?>">
                <label for="produto">Produto:</label>
                <input type="text" id="produto" name="produto" required>

                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>

                <label for="descricao">Descrição do Pedido:</label>
                <textarea style="margin-bottom: 15px;" id="descricao" name="descricao" rows="4" required></textarea>

                <input type="submit" value="Enviar">
            </form>
        </div>

        <script>
            document.getElementById('order-form').addEventListener('submit', function(event) {
                var fornecedor = document.getElementById('fornecedor').value;
                var produto = document.getElementById('produto').value;
                var quantidade = document.getElementById('quantidade').value;
                var descricao = document.getElementById('descricao').value;

                var email = document.getElementById('email').value;
                var subject = encodeURIComponent('Orçamento');
                var body = encodeURIComponent(
                    'Produto: ' + produto + '\n' +
                    'Quantidade: ' + quantidade + '\n' +
                    'Descrição: ' + descricao
                );

                var mailtoLink = 'mailto:' + email + '?subject=' + subject + '&body=' + body;

                // Define a flag para indicar que o redirecionamento do mailto deve ocorrer
                localStorage.setItem('redirectToMailto', 'true');
                localStorage.setItem('mailtoLink', mailtoLink);
            });

            window.onload = function() {
                // Verifica a flag para redirecionamento do mailto
                if (localStorage.getItem('redirectToMailto') === 'true') {
                    var mailtoLink = localStorage.getItem('mailtoLink');
                    localStorage.removeItem('redirectToMailto');
                    localStorage.removeItem('mailtoLink');
                    window.location.href = mailtoLink;
                }
            };
        </script>
    </div>
    </div>

    <div class="container">
        <div class="table-container">
            <?php
            $sql_consulta = "
        SELECT 
            c.*, 
            f.nome_for
        FROM 
            compra c
        INNER JOIN 
            fornecedores f 
        ON 
            c.fornecedores_cod_for = f.cod_for
        WHERE 
            f.cod_for = c.fornecedores_cod_for
    ;";

            $resultado = mysqli_query($conectar, $sql_consulta);
            ?>
            <table class="table text-white">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Data</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Item</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Observações</th>
                        <th scope="col">Status</th>
                        <th scope="col">Fornecedores</th>
                        <th scope="col">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($dado = mysqli_fetch_assoc($resultado)) {
                        echo "<tr>";
                        echo "<td>" . $dado['cod_com'] . "</td>";
                        echo "<td>" . $dado['data_com'] . "</td>";
                        echo "<td>" . $dado['valor_com'] . "</td>";
                        echo "<td>" . $dado['item_com'] . "</td>";
                        echo "<td>" . $dado['qtd_com'] . "</td>";
                        echo "<td>" . $dado['observacao_com'] . "</td>";
                        echo "<td>" . $dado['status_com'] . "</td>";
                        echo "<td>" . $dado['nome_for'] . "</td>";
                        echo "<td>
                                <a class='btn btn-sm btn-primary' href='editar_compra.php?id=$dado[cod_com]' title='Editar'>
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