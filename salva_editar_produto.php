<?php
include_once 'funcao.php';
$conectar = mysqli_connect("localhost", "root", "", "erp");

if (!$conectar) {
    die("Conexão falhou: " . mysqli_connect_error());
}

$nome = clear($_POST["nome_prod"]);



// Consulta para obter os valores atuais do produto e da tabela estoque
$sql_consulta = "
    SELECT 
        p.*, 
        e.*
    FROM 
        produto p
    INNER JOIN 
        estoque e 
    ON 
        p.cod_prod = e.produto_cod_prod 
    WHERE 
        p.nome_prod = ?";
$stmt = $conectar->prepare($sql_consulta);
$stmt->bind_param("s", $nome);
$stmt->execute();
$resultado_consulta = $stmt->get_result();

if ($resultado_consulta->num_rows > 0) {
    $produto_atual = $resultado_consulta->fetch_assoc();

    // Função auxiliar para verificar se o valor do POST está vazio e usar o valor atual se necessário
    function verificarValor($postValue, $currentValue)
    {
        return !empty($postValue) ? $postValue : $currentValue;
    }

    // Atribui os valores do formulário ou mantém os valores atuais se os campos estiverem vazios
    $descricao = clear(verificarValor($_POST["descricao_prod"], $produto_atual["descricao_prod"]));
    $medida = clear(verificarValor($_POST["medida_prod"], $produto_atual["medida_prod"]));
    $estoqueMin = clear(verificarValor($_POST["estoque_minimo_est"], $produto_atual["estoque_minimo_est"]));
    $tipo = clear(verificarValor($_POST["tipo_prod"], $produto_atual["tipo_prod"]));
    $preco = clear(verificarValor($_POST["preco_prod"], $produto_atual["preco_prod"]));
    $foto = clear(verificarValor('img/' . $_POST["foto_prod"], 'img/' . $produto_atual["foto_prod"]));
    $quantidade = clear(verificarValor($_POST["quantidade_est"], $produto_atual["quantidade_est"]));

    // Query de atualização para a tabela produto
    $sql_altera_produto = "UPDATE produto SET 
        descricao_prod = ?, 
        medida_prod = ?,  
        tipo_prod = ?, 
        preco_prod = ?, 
        foto_prod = ? 
        WHERE nome_prod = ?";

    $stmt_produto = $conectar->prepare($sql_altera_produto);
    $stmt_produto->bind_param("ssssss", $descricao, $medida, $tipo, $preco, $foto, $nome);

    // Query de atualização para a tabela estoque, atualizando apenas a quantidade
    $sql_altera_estoque = "UPDATE estoque SET 
        quantidade_est = ?, 
        estoque_minimo_est = ?
        WHERE produto_cod_prod = ?";
    
    $stmt_estoque = $conectar->prepare($sql_altera_estoque);
    $stmt_estoque->bind_param("dii", $quantidade, $estoqueMin, $produto_atual["cod_prod"]);

    // Executa as queries de atualização
    if ($stmt_produto->execute() && $stmt_estoque->execute()) {
        echo "<script>
                alert('$nome alterado com sucesso');
                location.href = 'editar_produto.php';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao atualizar os dados do produto');
                location.href = 'editar_produto.php';
              </script>";
    }

    $stmt_produto->close();
    $stmt_estoque->close();
} else {
    echo "<script>
            alert('Produto não encontrado.');
            location.href = 'editar_produto.php';
          </script>";
}

$conectar->close();
?>
