<?php
include_once "funcao.php";
$conectar = mysqli_connect("localhost", "root", "", "erp");

if (!$conectar) {
    die("Conexão falhou: " . mysqli_connect_error());
}

$id = $_POST["id"];
$nome = clear($_POST["nome"]);
$email = clear($_POST["email"]);
$endereco = clear($_POST["endereco"]);
$data_nasc = clear($_POST["data"]);
$telefone = clear($_POST["telefone"]);
$funcao = clear($_POST["funcao"]);
$login = clear($_POST["login"]);
$senha = $_POST["senha"]; // Senha vinda do formulário
$salario = clear($_POST["salario"]);
$feed = clear($_POST["feed"]);
$status = clear($_POST["status"]);
$foto = clear('img/' . $_POST["foto"]);

// Se $senha estiver vazia, mantém a senha atual do banco de dados
if (empty($senha)) {
    $sql_senha = "SELECT senha_fun FROM funcionario WHERE cod_fun = ?";
    $stmt_senha = $conectar->prepare($sql_senha);
    $stmt_senha->bind_param("i", $id);
    $stmt_senha->execute();
    $stmt_senha->bind_result($senha_atual);
    $stmt_senha->fetch();
    $stmt_senha->close();

    $criptografia = $senha_atual; // Mantém a senha atual
} else {
    // Criptografa a nova senha informada
    $criptografia = password_hash($senha, PASSWORD_DEFAULT);
}

if (isset($_POST["permissoes"]) && is_array($_POST["permissoes"])) {
    $permissoes = implode(",", $_POST["permissoes"]);
} else {
    $permissoes = "";
}

// Verifica se o nome do funcionário já está cadastrado em outro registro
$sql_verifica = "SELECT nome_fun FROM funcionario WHERE nome_fun = ? AND cod_fun != ?";
$stmt = $conectar->prepare($sql_verifica);
$stmt->bind_param("si", $nome, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>
            alert('$nome já foi cadastrado');
            location.href = 'editar_fun.php?id=$id';
          </script>";
    $stmt->close();
    $conectar->close();
    exit();
}

$stmt->close();

// Query de atualização para a tabela funcionario
$sql_update = "UPDATE funcionario
               SET nome_fun = ?, email_fun = ?, endereco_fun = ?, nascimento_fun = ?, tel_fun = ?, funcao_fun = ?, permissao_fun = ?, login_fun = ?, senha_fun = ?, salario_fun = ?, feedback_fun = ?, status_fun = ?, foto_fun = ?
               WHERE cod_fun = ?";
$stmt = $conectar->prepare($sql_update);
$stmt->bind_param("ssssssssssssis", $nome, $email, $endereco, $data_nasc, $telefone, $funcao, $permissoes, $login, $criptografia, $salario, $feed, $status, $foto, $id);

if ($stmt->execute()) {
    echo "<script>
            alert('Funcionário atualizado com sucesso!');
            location.href = 'funcionarios.php';
          </script>";
} else {
    echo "<script>
            alert('Erro ao atualizar funcionário!');
            location.href = 'editar_funcionario.php?id=$id';
          </script>";
}

$stmt->close();
$conectar->close();
?>
