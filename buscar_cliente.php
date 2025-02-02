<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

$cpf = $_POST['cpf'] ?? '';  // Garantir que o valor de 'cpf' não seja nulo

// Usar prepared statements para evitar SQL injection
$sql = "SELECT cod_cli, nome_cli FROM cliente WHERE cpf_cli = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $cpf);  // "s" significa string
$stmt->execute();
$result = $stmt->get_result();

// Definir o cabeçalho da resposta como JSON
header('Content-Type: application/json');

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    $_SESSION['nome_cli'] = $cliente['nome_cli'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cliente não encontrado.']);
}

$stmt->close();
$conexao->close();
?>
