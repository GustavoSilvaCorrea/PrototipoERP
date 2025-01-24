<?php
session_start();
include_once "funcao.php"; // Certifique-se de incluir suas funções de sanitização aqui, se necessário

$conectar = mysqli_connect("localhost", "root", "", "erp");

if (!$conectar) {
    die("Conexão falhou: " . mysqli_connect_error());
}

$id = $_POST["id"];
$nome = clear($_POST["nome"]);
$email = clear($_POST["email"]);
$cnpj = clear($_POST["cnpj"]);
$endereco = clear($_POST["endereco"]);
$tel = clear($_POST["telefone"]);

// Verifica se o nome do fornecedor já está cadastrado em outro registro
$sql_verifica = "SELECT nome_for FROM fornecedores WHERE nome_for = ? AND cod_for != ?";
$stmt = $conectar->prepare($sql_verifica);
$stmt->bind_param("si", $nome, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>
            alert('$nome já está cadastrado');
            location.href = 'editar_for.php?id=$id';
          </script>";
    $stmt->close();
    $conectar->close();
    exit();
}

$stmt->close();

// Query de atualização para a tabela fornecedores
$sql_update = "UPDATE fornecedores
               SET nome_for = ?, cnpj_cpf_for = ?, endereco_for = ?, email_for = ?, tel_for = ?
               WHERE cod_for = ?";
$stmt = $conectar->prepare($sql_update);
$stmt->bind_param("sssssi", $nome, $cnpj, $endereco, $email, $tel, $id);

if ($stmt->execute()) {
    echo "<script>
            alert('Fornecedor atualizado com sucesso!');
            location.href = 'fornecedores.php';
          </script>";
} else {
    echo "<script>
            alert('Erro ao atualizar o fornecedor!');
            location.href = 'editar_fornecedor.php?id=$id';
          </script>";
}

$stmt->close();
$conectar->close();
?>
