<?php 
$conectar = mysqli_connect("localhost", "root", "", "erp");

$cod = $_POST['cod_for'];
$produto = $_POST['produto'];
$quantidade = $_POST['quantidade'];
$descricao = $_POST['descricao'];

$sql = "INSERT INTO compra (data_com, item_com, qtd_com, observacao_com, status_com, fornecedores_cod_for) VALUES (now(), '$produto', $quantidade, '$descricao', 'P', '$cod')";

if ($conectar->query($sql) === TRUE) {
    echo "<script>
                alert('Pedido realizado com sucesso.');
                location.href = 'compra.php';
        </script>";
} else {
    "<script>
                alert('Ocorreu um erro ao realizar o pedido. Tente novamente.');
                location.href = 'compra.php';
    </script>";
}
