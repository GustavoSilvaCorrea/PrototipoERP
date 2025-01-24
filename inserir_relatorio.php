<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verifica se já existe um relatório financeiro para o mês atual
$mes_ano_atual = date('Y-m-d');
$sql_verifica_relatorio = "SELECT data_mon FROM relatorio_monetario WHERE data_mon = '$mes_ano_atual'";
$resultado_verifica_relatorio = mysqli_query($conexao, $sql_verifica_relatorio);

if (mysqli_num_rows($resultado_verifica_relatorio) === 0) {
    
    // Conta o número de registros na tabela compra
    $sql_conta_compra = "SELECT COUNT(*) AS total_compras FROM compra";
    $resultado_conta_compra = mysqli_query($conexao, $sql_conta_compra);
    $linha_compra = mysqli_fetch_assoc($resultado_conta_compra);
    $total_compras = $linha_compra['total_compras'];

    // Conta o número de registros na tabela venda
    $sql_conta_venda = "SELECT COUNT(*) AS total_vendas FROM venda";
    $resultado_conta_venda = mysqli_query($conexao, $sql_conta_venda);
    $linha_venda = mysqli_fetch_assoc($resultado_conta_venda);
    $total_vendas = $linha_venda['total_vendas'];

    $sql_consulta = "SELECT compra.cod_com AS cod_compra, venda.cod_venda AS cod_venda
    FROM compra
    INNER JOIN venda ON compra.cod_com = venda.cod_venda";
    $resultado_consulta = mysqli_query($conexao, $sql_consulta);

    while ($linha = mysqli_fetch_assoc($resultado_consulta)) {
        $cod_compra = $linha['cod_compra'];
        $cod_venda = $linha['cod_venda'];

        $sql_insere_relatorio = "INSERT INTO relatorio_monetario (data_mon, nr_vendas_mon, nr_compras_mon, compra_cod_com, venda_cod_venda) VALUES ('$mes_ano_atual', $total_vendas, $total_compras, $cod_compra, $cod_venda)";
        mysqli_query($conexao, $sql_insere_relatorio);
    }
} else {
    echo '<p>Erro, Já existe um Relatorio da data atual.</p>';
}

exit;
