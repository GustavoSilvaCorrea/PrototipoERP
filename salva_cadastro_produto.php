<?php
include_once "funcao.php";
$conectar = mysqli_connect("localhost", "root", "", "erp");

if (!$conectar) {
    die("Connection failed: " . mysqli_connect_error());
}

// Preparação dos dados recebidos do formulário
$nome = clear($_POST["nome_prod"]);
$descricao = clear($_POST["descricao_prod"]);
$medida = clear($_POST["medida_prod"]);
$quantidade = clear($_POST["quantidade_est"]);
$estoqueMin = clear($_POST["estoque_min_prod"]);
$tipo = clear($_POST["tipo_prod"]);
$preco = clear($_POST["preco_prod"]);
$foto = clear('img/' . $_POST["foto_prod"]);

// Verifica se o nome do produto já está cadastrado
$sql_consulta = "SELECT nome_prod FROM produto WHERE nome_prod = ?";
$stmt = $conectar->prepare($sql_consulta);
$stmt->bind_param("s", $nome);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Nome do produto já cadastrado
    echo "<script>
            alert('$nome já está cadastrado.');
            location.href = 'cadastro_produtos.php';
          </script>";
} else {
    // Verifica se o produto já existe no estoque e atualiza ou insere novo produto e estoque
    $sql_cadastrar = "INSERT INTO produto (nome_prod, descricao_prod, medida_prod, tipo_prod, preco_prod, foto_prod)
                      VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conectar->prepare($sql_cadastrar);
    $stmt->bind_param("ssssss", $nome, $descricao, $medida, $tipo, $preco, $foto);

    if ($stmt->execute()) {
        $cod_prod = $stmt->insert_id;
        echo "<script>alert('$nome foi cadastrado com sucesso');</script>";


        $sql_consulta_compra = "SELECT cod_com FROM compra WHERE item_com = '$nome' and status_com = 'A'";
        $resultado = mysqli_query($conectar, $sql_consulta_compra);

        if (mysqli_num_rows($resultado) > 0) {
            $linha = mysqli_fetch_assoc($resultado);
            $cod_com = $linha['cod_com'];

            // Insere novo registro no estoque para o novo produto
            $sql_cadastro_estoque = "INSERT INTO estoque (quantidade_est, data_entrada_est, estoque_minimo_est, compra_cod_com, produto_cod_prod)
                                 VALUES (?, NOW(), ?, ?, ?)";
            $stmt = $conectar->prepare($sql_cadastro_estoque);
            if ($stmt) {
                $stmt->bind_param("diii", $quantidade, $estoqueMin, $cod_com, $cod_prod);
                $stmt->execute();
                $stmt->close();
                echo "<script>
                    alert('O produto $nome foi cadastrado no estoque com sucesso.');
                    location.href = 'estoque.php';
                  </script>";
            } else {
                echo "<script>
                    alert('Ocorreu um erro ao cadastrar no estoque. Tente novamente.');
                    location.href = 'cadastro_produtos.php';
                  </script>";
            }
        } else {
            echo "<script>
                alert('Ocorreu um erro no servidor. Tente novamente.');
                location.href = 'cadastro_produtos.php';
              </script>";
        }
    }

    // Fecha a declaração e a conexão
    $stmt->close();
    $conectar->close();
}
