<?php
include_once "funcao.php";

$connect = mysqli_connect("localhost", "root", "", "erp");

// Preparação dos dados recebidos do formulário
$nome = clear($_POST["nome"]);
$cpf = clear($_POST["cpf"]);
$email = clear($_POST["email"]);
$endereco = clear($_POST["endereco"]);
$data_nasc = clear($_POST["data"]);
$telefone = clear($_POST["telefone"]);
$funcao = clear($_POST["funcao"]);
$salario = clear($_POST["salario"]);
$login = clear($_POST["login"]);
$status = clear($_POST["status"]);
$senha = $_POST["senha"];
$feedback = clear($_POST["feedback"]);
$foto = clear('img/' . $_POST["foto"]);

if (isset($_POST["permissoes"]) && is_array($_POST["permissoes"])) {
    $permissoes = implode(",", $_POST["permissoes"]);
} else {
    $permissoes = "";
}

$criptografia = password_hash($senha, PASSWORD_DEFAULT);

// Verifica se o nome já está cadastrado
$sql_consulta = "SELECT nome_fun FROM funcionario WHERE nome_fun = ?";
$stmt = $connect->prepare($sql_consulta);
$stmt->bind_param("s", $nome);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Nome já cadastrado
    echo "<script>
            alert('$nome já foi cadastrado');
            location.href = funcionarios.php';
          </script>";
} else {
    // Nome não cadastrado, prosseguir com a inserção
    $sql_cadastra = "INSERT INTO funcionario 
                    (nome_fun, cpf_fun, nascimento_fun, endereco_fun, email_fun, tel_fun, funcao_fun, permissao_fun, status_fun, login_fun, senha_fun, data_adimissao_fun, salario_fun, foto_fun, feedback_fun) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    
    $stmt = $connect->prepare($sql_cadastra);
    $stmt->bind_param("ssssssssssssds", $nome, $cpf, $data_nasc, $endereco, $email, $telefone, $funcao, $permissoes, $status, $login, $criptografia, $salario, $foto, $feedback);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastrado com sucesso!'); location.href= 'funcionarios.php';</script>";
    } else {
        echo "<script>alert('Erro ao Cadastrar!'); location.href= 'funcionarios.php';</script>";
    }
}

// Fecha a declaração e a conexão
$stmt->close();
$connect->close();
?>
