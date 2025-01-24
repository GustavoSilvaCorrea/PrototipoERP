<?php
session_start();
$conexao = mysqli_connect("localhost", "root", "", "erp");
if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}
// Adicionar produto ao pedido
if (isset($_POST['add_produto'])) {
    $produto_id = $_POST['produto_id'];
    $produto_nome = $_POST['produto_nome'];
    $produto_preco = $_POST['produto_preco'];
    $produto_quantidade = $_POST['produto_quantidade'];
    if (!isset($_SESSION['pedido'])) {
        $_SESSION['pedido'] = [];
    }
    $_SESSION['pedido'][] = [
        'cod_prod' => $produto_id,
        'nome_prod' => $produto_nome,
        'preco_prod' => $produto_preco,
        'quantidade' => $produto_quantidade
    ];
}
// Inicializar 'pedido' se não estiver definido
if (!isset($_SESSION['pedido'])) {
    $_SESSION['pedido'] = [];
}
// Finalizar pedido
if (isset($_POST['finalizar_pedido'])) {
    $forma_pagamento = $_POST['forma_pagamento'];
    $descricao_venda = $_POST['descricao_venda'];
    $total_pedido = array_sum(array_map(function ($item) {
        return $item['preco_prod'] * $item['quantidade'];
    }, $_SESSION['pedido']));
    if ($forma_pagamento == 'dinheiro') {
        $valor_dinheiro = floatval($_POST['valor_dinheiro']);
        if ($valor_dinheiro < $total_pedido) {
            $valor_faltante = $total_pedido - $valor_dinheiro;
            $mensagem = "Valor em dinheiro insuficiente! Faltam R$ " . number_format($valor_faltante, 2, ',', '.');
            echo "<script>
                alert('$mensagem');
                document.getElementById('total-pedido').textContent = 'R$ " . number_format($valor_faltante, 2, ',', '.') . "';
              </script>";
        } else {
            $troco = $valor_dinheiro - $total_pedido;
            $mensagem = "Pedido finalizado! Troco: R$ " . number_format($troco, 2, ',', '.');
            echo "<script>
            alert('$mensagem');
            location.href = 'sistema_venda.php';
          </script>";
            // Limpar o pedido atual
            unset($_SESSION['pedido']);
        }
    } else {
        $funcionario_id = $_SESSION['cod_fun']; // Assumindo que o ID do funcionário está armazenado na sessão
        $nome_cli = $_SESSION['nome_cli'];
        foreach ($_SESSION['pedido'] as $item) {
            $produto_nome = $item['nome_prod'];
            $produto_cod = $item['cod_prod'];
            $quantidade = $item['quantidade']; // Usar a quantidade especificada pelo usuário
            $preco_unitario = $item['preco_prod'];
            // Consultar o estoque para o produto
            $sql_consulta_est = "SELECT quantidade_est, estoque_minimo_est, cod_est FROM estoque WHERE produto_cod_prod = '$produto_cod'";
            $resultado_est = mysqli_query($conexao, $sql_consulta_est);
            if (mysqli_num_rows($resultado_est) > 0) {
                $estoque = mysqli_fetch_assoc($resultado_est);
                $quantidade_disponivel = $estoque['quantidade_est'];
                $estoque_minimo = $estoque['estoque_minimo_est'];
                $cod_est = $estoque['cod_est'];
                if ($quantidade > $quantidade_disponivel) {
                    $mensagem = "Quantidade requisitada para o produto $produto_nome excede a quantidade em estoque! Disponível: $quantidade_disponivel, Requisitado: $quantidade.";
                    echo "<script>
                        alert('$mensagem');
                        location.href = 'sistema_venda.php';
                      </script>";
                    exit();
                } elseif ($quantidade_disponivel - $quantidade < $estoque_minimo) {
                    $mensagem = "A venda do produto $produto_nome deixará o estoque abaixo do mínimo permitido! Estoque mínimo: $estoque_minimo.";
                    echo "<script>
                        alert('$mensagem');
                        location.href = 'sistema_venda.php';
                      </script>";
                    exit();
                } else {
                    // Inserção na tabela venda
                    $sql_inserir_venda = "
                        INSERT INTO venda (data_venda, valor_total_venda, forma_pagamento_venda, descricao_venda, nome_cliente_venda, funcionario_cod_fun, produto_venda, quantidade_venda, custo_venda, estoque_cod_est)
                        VALUES (NOW(), '$total_pedido', '$forma_pagamento', '$descricao_venda', '$nome_cli', '$funcionario_id', '$produto_nome', '$quantidade', '$preco_unitario' ,'$cod_est')
                    ";
                    mysqli_query($conexao, $sql_inserir_venda);
                    // Atualização do estoque
                    $sql_atualizar_estoque = "
                        UPDATE estoque
                        SET quantidade_est = quantidade_est - $quantidade,
                        data_saida_est = now()
                        WHERE produto_cod_prod = $produto_cod
                    ";
                    mysqli_query($conexao, $sql_atualizar_estoque);
                }
            } else {
                echo "<script>
                    alert('Produto não encontrado no estoque! $produto_cod');
                    location.href = 'sistema_venda.php';
                  </script>";
                exit();
            }
        }
        $mensagem = "Pedido finalizado!";
        echo "<script>
        alert('$mensagem');
        location.href = 'sistema_venda.php';
      </script>";
        // Limpar o pedido atual
        unset($_SESSION['pedido']);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de venda</title>
    <link rel="stylesheet" href="formulariopadrao.css">
    <style>
        /* Estilização dos resultados de produtos */
        #resultados-produto {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }

        #cabecalho-main {
            display: flex;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-bottom-style: hidden;
            background-color: #44749D;
            color: #fff;
            padding: 1em;
            text-align: center;
            align-items: center;
        }

        #cabecalho-main .img_fun {
            width: 20%;
            margin: 2px;
            padding: 2px;
            border-radius: 50%;
        }

        #cabecalho-main .img {
            align-content: left;
            padding: 1% 1%;
            height: 92%;
            width: 16%;
            justify-content: center;
            cursor: pointer;
            margin-left: 7%;
        }

        #cabecalho-main h3 {
            margin: 0 10px;
            margin-left: auto;
        }

        #resultados-produto div {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #resultados-produto div:hover {
            background-color: #eef;
        }

        /* Lista do pedido atual */
        #lista-pedido {
            background-color: #fff;
            padding-left: 260px;
            padding-right: 260px;
            padding-top: 15px;
            padding-bottom: 15px;
            border: 1px solid black;
            border-radius: 5px;
            margin-top: 50px;
            margin-bottom: 0;
        }

        #lista-pedido div {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        #total-pedido {
            font-weight: bold;
            margin-top: 10px;
        }

        .vendas {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php"; ?>
    <?php
    if (isset($_POST['cpf'])) {
        $cpf = $_POST['cpf'];
        $sql_verificar_cliente = "SELECT cod_cli, nome_cli, tel_cli, endereco_cli, email_cli FROM cliente WHERE cpf_cli = '$cpf'";
        $resultado_cpf = mysqli_query($conexao, $sql_verificar_cliente);

        if (mysqli_num_rows($resultado_cpf) > 0) {
            $cliente = mysqli_fetch_assoc($resultado_cpf);
            $_SESSION['nome_cli'] = $cliente['nome_cli'];
            echo "<h1>Cliente encontrado: {$cliente['nome_cli']}</h1>";
        } else {
            echo "<h1>Cliente não encontrado. Por favor, cadastre o cliente.</h1>";
            echo '<div class="vendas"><div class="form-container">' .
                '<form method="POST" action="salva_cadastro_cliente.php">' .
                'CPF: <input type="text" name="cpf" value="' . $cpf . '" required><br>' .
                'Nome: <input type="text" name="nome" required><br>' .
                'Telefone: <input type="text" name="telefone"><br>' .
                'Endereço: <input type="text" name="endereco"><br>' .
                'Email: <input type="text" name="email"><br>' .
                'Observação: <input type="text" name="descricao"><br>' .
                '<input type="submit" value="Cadastrar">' .
                '</form></div></div>
                <hr>';
        }
    }

    ?>
    <div class="vendas">
        <div class="form-container">
            <form method="post">
                <h3>Verificar o cadastro do cliente</h3>
                CPF: <input type="text" name="cpf" pattern="[0-9]{3}[0-9]{3}[0-9]{3}[0-9]{2}" required><br>
                <input type="submit" value="Verificar Cadastro">
            </form>
        </div>
        <hr>
        <div class="form-container">
            <form method="POST">
                <label for="busca-produto">Buscar Produto:</label>
                <input type="text" id="busca-produto" name="busca_produto" placeholder="Nome ou Código do Produto">
                <button type="submit" name="buscar">Buscar</button>
            </form>
        </div>
        <?php
        if (isset($_POST['buscar'])) {
            $query = $_POST['busca_produto'];
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE '%$query%' OR cod_prod LIKE '%$query%'";
            $result = mysqli_query($conexao, $sql);
            $produtos = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $produtos[] = $row;
            }
            if (!empty($produtos)) {
                echo '<div id="resultados-produto">';
                echo '<p>Clique em cima do produto para adicionar ele ao pedido.</p>';
                foreach ($produtos as $produto) {
                    echo '<div onclick="adicionarProduto(' . $produto['cod_prod'] . ', \'' . $produto['nome_prod'] . '\', ' . $produto['preco_prod'] . ')">';
                    echo 'Código: ' . $produto['cod_prod'] . ' - Nome: ' . $produto['nome_prod'] . ' - Preço: R$ ' . number_format($produto['preco_prod'], 2, ',', '.');
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo 'Nenhum produto encontrado.';
            }
        }
        ?>
        <script>
            // Função que será chamada para adicionar um produto ao carrinho
            function adicionarProduto(codProd, nomeProd, precoProd) {
                // Solicita ao usuário que informe a quantidade desejada
                const quantidade = prompt("Informe a quantidade:", 1);
                // Verifica se o usuário inseriu uma quantidade válida e maior que zero
                if (quantidade != null && quantidade > 0) {
                    // Cria um elemento <form> dinamicamente
                    var form = document.createElement('form');
                    form.method = 'POST'; // Define o método de envio como POST
                    form.style.display = 'none'; // Esconde o formulário visualmente
                    // Cria um campo de input hidden para o ID do produto
                    var inputCod = document.createElement('input');
                    inputCod.type = 'hidden'; // Tipo hidden para não aparecer no formulário
                    inputCod.name = 'produto_id'; // Nome do campo que será enviado
                    inputCod.value = codProd; // Valor do ID do produto
                    // Cria um campo de input hidden para o nome do produto
                    var inputNome = document.createElement('input');
                    inputNome.type = 'hidden';
                    inputNome.name = 'produto_nome';
                    inputNome.value = nomeProd; // Valor do nome do produto
                    // Cria um campo de input hidden para o preço do produto
                    var inputPreco = document.createElement('input');
                    inputPreco.type = 'hidden';
                    inputPreco.name = 'produto_preco';
                    inputPreco.value = precoProd; // Valor do preço do produto
                    // Cria um campo de input hidden para a quantidade do produto
                    var inputQuantidade = document.createElement('input');
                    inputQuantidade.type = 'hidden';
                    inputQuantidade.name = 'produto_quantidade';
                    inputQuantidade.value = quantidade; // Valor da quantidade do produto
                    // Cria um campo de input hidden para indicar que está adicionando o produto
                    var inputAdd = document.createElement('input');
                    inputAdd.type = 'hidden';
                    inputAdd.name = 'add_produto';
                    inputAdd.value = '1'; // Valor para identificar que está adicionando o produto
                    // Adiciona os campos criados ao formulário
                    form.appendChild(inputCod);
                    form.appendChild(inputNome);
                    form.appendChild(inputPreco);
                    form.appendChild(inputQuantidade);
                    form.appendChild(inputAdd);
                    // Adiciona o formulário ao corpo (body) do documento HTML
                    document.body.appendChild(form);
                    // Submete o formulário automaticamente
                    form.submit();
                }
            }
        </script>

        <div id="lista-pedido">
            <h3>Pedido Atual</h3>
            <form method="post">
                <?php
                // Inicializar variáveis
                $total_pedido = 0;
                // Inicializar pedido na sessão, se não estiver definido
                if (!isset($_SESSION['pedido'])) {
                    $_SESSION['pedido'] = [];
                }
                // Verificar se o botão de limpar pedido foi clicado
                if (isset($_POST['limpar_pedido'])) {
                    // Limpar a variável de sessão
                    unset($_SESSION['pedido']);
                    // Redirecionar usando JavaScript para evitar o erro de headers
                    echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
                    exit;
                }
                // Verificar se existe pedido na sessão
                if (isset($_SESSION['pedido']) && !empty($_SESSION['pedido'])) {
                    // Exibir os itens do pedido
                    foreach ($_SESSION['pedido'] as $item) {
                        echo '<div>';
                        echo 'Nome: ' . $item['nome_prod'] . ' - Preço: R$ ' . number_format($item['preco_prod'], 2, ',', '.') . ' - Quantidade: ' . $item['quantidade'];
                        echo '</div>';
                        $total_pedido += $item['preco_prod'] * $item['quantidade'];
                    }
                    echo '<div id="total-pedido">Total: R$ ' . number_format($total_pedido, 2, ',', '.') . '</div>';
                } else {
                    // Caso não haja itens no pedido
                    echo '<p>Nenhum item no pedido.</p>';
                    echo '<p>Realiza uma busca no campo acima com o codigo ou nome do produto.</p>';
                }
                ?>
                <!-- Botão para limpar o pedido -->
                <input type="submit" name="limpar_pedido" value="Limpar Pedido">
            </form>
        </div>
        <script>
            // Caso a opção de pagamento for dinheiro, aparecer a opção de colocar o valor do dinheiro 
            function atualizarFormaPagamento() {
                const formaPagamento = document.querySelector('select[name="forma_pagamento"]').value;
                const valorDinheiroDiv = document.getElementById('valor-dinheiro-div');
                switch (formaPagamento) {
                    case 'dinheiro':
                        valorDinheiroDiv.style.display = 'block';
                        break;
                    default:
                        valorDinheiroDiv.style.display = 'none';
                        break;
                }
            }
        </script>
        <hr>
        <div class="form-container" style="margin-bottom: 40px;">
            <form method="post">
                <h3>Finalizar Pedido</h3>
                <label for="forma_pagamento">Forma de Pagamento:</label>
                <select name="forma_pagamento" onchange="atualizarFormaPagamento()">
                    <option value="dinheiro">Dinheiro</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Débito</option>
                    <option value="pix">PIX</option>
                </select>
                <div id="valor-dinheiro-div" style="display: none;">
                    <label for="valor_dinheiro">Valor em Dinheiro:</label>
                    <input type="number" step="0.01" name="valor_dinheiro">
                </div>
                <label for="descricao_venda">Descrição:</label>
                <textarea name="descricao_venda"></textarea>
                <button type="submit" name="finalizar_pedido">Finalizar Pedido</button>
            </form>
        </div>
    </div>
</body>

</html>