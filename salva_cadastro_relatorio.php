<?php
include_once "funcao.php";

$conectar = mysqli_connect("localhost", "root", "", "erp");

// Verifica se a conexão foi bem-sucedida
if (mysqli_connect_errno()) {
    die("Falha ao conectar ao MySQL: " . mysqli_connect_error());
}

$titulo = clear($_POST["titulo"]);
$tipo = clear($_POST["tipo"]);
$nivel = clear ($_POST["nivel"]);
$conteudo = clear ($_POST["conteudo"]);
$nome = $_SESSION["nome_fun"];

// Busca o código do funcionário pelo nome
$sql_consulta = "SELECT cod_fun FROM funcionario WHERE nome_fun = '$nome'";
$resultado = mysqli_query($conectar, $sql_consulta);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_assoc($resultado);
    $cod_fun = $row['cod_fun'];

    $sql_cadastro = "INSERT INTO relatorio (tipo_rel, titulo_rel, conteudo_rel, nivel_rel, funcionario_cod_fun)
                     VALUES ('$tipo', '$titulo', '$conteudo', '$nivel', '$cod_fun')";
    $resultado_cadastro = mysqli_query($conectar, $sql_cadastro);

    if ($resultado_cadastro) {
        echo "<script>
                alert('Relatório realizado com sucesso.');
                location.href = 'relatorios.php';
              </script>";
    } else {
        echo "<script>
                alert('Ocorreu um erro ao realizar o relatório. Tente novamente.');
                location.href = 'cadastro_relatorios.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Funcionário não encontrado.');
            location.href = 'cadastrar_relatorios.php';
          </script>";
}
?>
