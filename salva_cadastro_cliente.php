<?php
include_once "funcao.php";
$conexao = mysqli_connect("localhost", "root", "", "erp");

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = clear ($_POST['cpf']);
    $nome = clear ($_POST['nome']);
    $telefone = clear ($_POST['telefone']);
    $endereco = clear ($_POST['endereco']);
    $email = clear ($_POST['email']);
    $descricao = clear ($_POST['descricao']);
    $nome_fun = clear ($_SESSION["nome_fun"]);

    // Verificar se o CPF já existe na base de dados
    $sql_verifica_cpf = "SELECT cod_cli FROM cliente WHERE cpf_cli = '$cpf'";
    $resultado_verifica_cpf = mysqli_query($conexao, $sql_verifica_cpf);

    if ($resultado_verifica_cpf && mysqli_num_rows($resultado_verifica_cpf) > 0) {
        // CPF já existe, retornar o código do cliente
        $row = mysqli_fetch_assoc($resultado_verifica_cpf);
        $cod_cli_existente = $row['cod_cli'];

        echo "<script>
                alert('CPF já cadastrado. Nome do Cliente: $nome');
                location.href = 'sistema_venda.php';
              </script>";
    } else {
        // CPF não existe, prosseguir com o cadastro
        $sql_consulta_codigo_fun = "SELECT cod_fun FROM funcionario WHERE nome_fun = '$nome_fun'";
        $resultado = mysqli_query($conexao, $sql_consulta_codigo_fun);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $cod_fun = $row['cod_fun'];

            $sql_adiciona_cliente = "INSERT INTO cliente (cpf_cli, nome_cli, tel_cli, endereco_cli, email_cli, descricao_cli, funcionario_cod_fun)
            VALUES ('$cpf', '$nome', '$telefone', '$endereco', '$email', '$descricao', '$cod_fun')";
            $resultado_cadastro = mysqli_query($conexao, $sql_adiciona_cliente);
            

            if ($resultado_cadastro) {
                echo "<script>
                        alert('Cliente cadastrado com sucesso.');
                        location.href = 'sistema_venda.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Ocorreu um erro ao realizar o cadastro. Tente novamente.');
                        location.href = 'sistema_venda.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Funcionário não encontrado.');
                    location.href = 'sistema_venda.php';
                  </script>";
        }
    }

    mysqli_close($conexao);
}
