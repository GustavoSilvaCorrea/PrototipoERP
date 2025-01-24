<?php

$conectar = mysqli_connect("localhost", "root", "", "erp");

if (!$conectar) {
    die("Conexão falhou: " . mysqli_connect_error());
}

$nome = $_POST["nome"];
$cnpj = $_POST["cnpj"];
$endereco = $_POST["endereco"];
$email = $_POST["email"];
$tel = $_POST["tel"];

// Verifica se o nome do fornecedor já está cadastrado
$sql_verifica = "SELECT nome_for FROM fornecedores WHERE nome_for = ?";
$stmt = $conectar->prepare($sql_verifica);
$stmt->bind_param("s", $nome);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>
            alert('$nome já foi cadastrado');
            location.href = 'fornecedores.php';
          </script>";
    $stmt->close();
    $conectar->close();
    exit();
}

$stmt->close();

// Se não existir fornecedor com o mesmo nome, cadastra o novo fornecedor
$sql_cadastra = "INSERT INTO fornecedores (nome_for, cnpj_cpf_for, endereco_for, email_for, tel_for) VALUES (?, ?, ?, ?, ?)";
$stmt = $conectar->prepare($sql_cadastra);
$stmt->bind_param("sssss", $nome, $cnpj, $endereco, $email, $tel);

if ($stmt->execute()) {
    echo "<script>
            alert('Fornecedor registrado com sucesso');
            location.href = 'fornecedores.php';
          </script>";
} else {
    echo "<script>
            alert('Ocorreu um erro ao cadastrar o fornecedor. Tente novamente.');
            location.href = 'fornecedores.php';
          </script>";
}

$stmt->close();
$conectar->close();
?>
