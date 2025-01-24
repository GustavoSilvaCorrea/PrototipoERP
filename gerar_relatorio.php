<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");

if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}


$ano_atual = date('Y');
$mes_atual = date('m');

// Subconsulta para obter o total de compras no mês atual
$sql_compras = "
    SELECT 
        COUNT(cod_com) AS nr_compras_mon, 
        SUM(valor_com) AS valor_total_compras_mon
    FROM 
        compra
    WHERE YEAR(data_com) = '$ano_atual' AND MONTH(data_com) = '$mes_atual'
    AND status_com = 'A'
";
$resultado_compras = mysqli_query($conexao, $sql_compras);
$dados_compras = mysqli_fetch_assoc($resultado_compras);

// Subconsulta para obter o total de vendas no mês atual
$sql_vendas = "
    SELECT 
        COUNT(cod_venda) AS nr_vendas_mon, 
        SUM(valor_total_venda) AS valor_total_vendas_mon
    FROM 
        venda
    WHERE YEAR(data_venda) = '$ano_atual' AND MONTH(data_venda) = '$mes_atual'
";
$resultado_vendas = mysqli_query($conexao, $sql_vendas);
$dados_vendas = mysqli_fetch_assoc($resultado_vendas);

$relatorio_html = '';

if ($dados_compras['nr_compras_mon'] > 0 || $dados_vendas['nr_vendas_mon'] > 0) {
    $relatorio_html .= '<h2>Dados do Relatório Gerado</h2>';
    $relatorio_html .= '<table>
                            <tr>
                                <th>Quantidade de Compras</th>
                                <th>Total de Compras</th>
                                <th>Quantidade de Vendas</th>
                                <th>Total de Vendas</th>
                                <th>Lucro</th>
                       
                            </tr>';

    $calculo_valor_total_compra = $dados_compras['valor_total_compras_mon'] ?? 0;
    $calculo_valor_total_venda = $dados_vendas['valor_total_vendas_mon'] ?? 0;
    $lucro = $calculo_valor_total_venda - $calculo_valor_total_compra;

    $relatorio_html .= '
                        <tr>
                            <td>' . ($dados_compras['nr_compras_mon'] ?? 0) . '</td>
                            <td>R$ ' . number_format($calculo_valor_total_compra, 2, ',', '.') . '</td>
                            <td>' . ($dados_vendas['nr_vendas_mon'] ?? 0) . '</td>
                            <td>R$ ' . number_format($calculo_valor_total_venda, 2, ',', '.') . '</td>
                            <td>R$ ' . number_format($lucro, 2, ',', '.') . '</td>
                        </tr>';

    $relatorio_html .= '</table>';
} else {
    $relatorio_html .= '<p>Nenhum compra ou venda encontrado para o mês atual.</p>';
}

echo $relatorio_html;

