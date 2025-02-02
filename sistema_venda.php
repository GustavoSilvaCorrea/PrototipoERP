<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$conexao = mysqli_connect("localhost", "root", "", "erp");
if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

// adicionar produto ao pedido
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
    echo json_encode($_SESSION['pedido']); // retorna o pedido atualizado
    exit;
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
            echo json_encode(['status' => 'error', 'message' => "Valor em dinheiro insuficiente! Faltam R$ " . number_format($valor_faltante, 2, ',', '.')]);
        } else {
            $troco = $valor_dinheiro - $total_pedido;
            echo json_encode(['status' => 'success', 'message' => "Pedido finalizado! Troco: R$ " . number_format($troco, 2, ',', '.')]);
            unset($_SESSION['pedido']);
        }
    } else {
        $funcionario_id = $_SESSION['cod_fun'];
        $nome_cli = $_SESSION['nome_cli'];
        foreach ($_SESSION['pedido'] as $item) {
            $produto_nome = $item['nome_prod'];
            $produto_cod = $item['cod_prod'];
            $quantidade = $item['quantidade'];
            $preco_unitario = $item['preco_prod'];
            $sql_consulta_est = "SELECT quantidade_est, estoque_minimo_est, cod_est FROM estoque WHERE produto_cod_prod = '$produto_cod'";
            $resultado_est = mysqli_query($conexao, $sql_consulta_est);
            if (mysqli_num_rows($resultado_est) > 0) {
                $estoque = mysqli_fetch_assoc($resultado_est);
                $quantidade_disponivel = $estoque['quantidade_est'];
                $estoque_minimo = $estoque['estoque_minimo_est'];
                $cod_est = $estoque['cod_est'];
                if ($quantidade > $quantidade_disponivel) {
                    echo json_encode(['status' => 'error', 'message' => "Quantidade requisitada para o produto $produto_nome excede a quantidade em estoque! Disponível: $quantidade_disponivel, Requisitado: $quantidade."]);
                    exit;
                } elseif ($quantidade_disponivel - $quantidade < $estoque_minimo) {
                    echo json_encode(['status' => 'error', 'message' => "A venda do produto $produto_nome deixará o estoque abaixo do mínimo permitido! Estoque mínimo: $estoque_minimo."]);
                    exit;
                } else {
                    $sql_inserir_venda = "INSERT INTO venda (data_venda, valor_total_venda, forma_pagamento_venda, descricao_venda, nome_cliente_venda, funcionario_cod_fun, produto_venda, quantidade_venda, custo_venda, estoque_cod_est) VALUES (NOW(), '$total_pedido', '$forma_pagamento', '$descricao_venda', '$nome_cli', '$funcionario_id', '$produto_nome', '$quantidade', '$preco_unitario', '$cod_est')";
                    mysqli_query($conexao, $sql_inserir_venda);
                    $sql_atualizar_estoque = "UPDATE estoque SET quantidade_est = quantidade_est - $quantidade, data_saida_est = now() WHERE produto_cod_prod = $produto_cod";
                    mysqli_query($conexao, $sql_atualizar_estoque);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => "Produto não encontrado no estoque! $produto_cod"]);
                exit;
            }
        }
        echo json_encode(['status' => 'success', 'message' => "Pedido finalizado!"]);
        unset($_SESSION['pedido']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Venda</title>
    <style>
        /* Estilos da Página Inteira */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
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
        }

        #cabecalho-main h3 {
            margin: 0 10px;
            margin-left: auto;
        }

        .vendas {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 10px;
            padding-bottom: 50px;
        }

        .form-container {
            width: 40%;
            height: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #fff;
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            /* Light and dark colors */
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
            /* Inner shadow for depth */
            /* Azul */
            border-radius: 20px;
            /* Sombra */
        }

        .form-container-produto {
            width: 40%;
            height: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #fff;
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            /* Light and dark colors */
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
            /* Inner shadow for depth */
            /* Azul */
            border-radius: 20px;
            /* Sombra */
        }

        .form-container-finalizar {
            width: 40%;
            height: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #fff;
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            /* Light and dark colors */
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
            /* Inner shadow for depth */
            /* Azul */
            border-radius: 20px;
            /* Sombra */
        }

        .oculto {
            display: none;
        }

        #itens-pedido {
            font-size: 20px;
        }

        #total-pedido {
            font-weight: 600;
            font-size: 25px;
        }

        .input-text {
            text-align: center;
            width: 600px;
            height: 70px;
            font-size: 25px;
            margin-bottom: 1rem;
            border: none;
            border-radius: 20px;
            background: #e0e0e0;
            /* Fundo dos inputs */
            color: #333;
            padding: 15px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Sombra interna */
        }

        .input-text:hover {
            background-color: #e0e0e0;
            border: 3px solid #365f7d;
            border-radius: 20px;
            /* Borda ao focar */
        }

        .input-submit {
            font-size: 1.5rem !important;
            width: 350px;
            height: 70px;
            padding: 15px;
            margin-top: 10px;
            background: #365f7d;
            /* Azul escuro */
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Sombra */
        }

        .input-submit:hover {
            background: #2a4d64;
            /* Azul mais escuro */
            transform: scale(1.05);
            /* Aumento ao passar o mouse */
        }

        #vava {
            width: 100%;
            display: flex;
            justify-content: space-evenly;
        }

        .lista-pedidos {
            height: 260px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-color: #fff;
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            /* Light and dark colors */
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
            /* Inner shadow for depth */
            /* Azul */
            border-radius: 20px;
            /* Sombra */
        }

        .centro {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #forma_pagamento {
            border: 5px solid;
            border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
            border-radius: 20px;
            padding: 10px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <header id="cabecalho-main">
        <?php include "iconpage.php" ?>
        <h3><?php echo $_SESSION["nome_fun"]; ?><?php include "valida_login.php"; ?></h3>
    </header>
    <?php include "navbar.php"; ?>
    <div class="vendas">
        <div id="cpf_cliente" class="form-container" style="background-image: url(img/person.png);">
            <form id="form-cpf">
                <h3 style="text-align: center; margin-bottom: 25px; font-size: 25px; font-weight: 600; color: #44749D;">Verificar o cadastro do cliente</h3>
                <input class="input-text" id="cpf" type="number" name="cpf" data-format="999.999.999-99" placeholder="Digite o CPF" required><br>
                <div id="resultados-cliente" style='display: none'></div>
                <input class="input-submit" type="submit" value="Verificar Cadastro">
            </form>
        </div>
        <div id="vava">
            <div id="busca-produto" class="form-container-produto oculto" style="background-image: url(img/produto.png);">
                    <h3 style="text-align: center; margin-bottom: 25px; font-size: 25px; font-weight: 600; color: #44749D;">Buscar Produto</h3>
                    <input type="text" id='produto' class="input-text" name="busca_produto" placeholder="Nome ou Código do Produto">
                    <div id="resultados-produto"></div>
                    <div class="centro">
                        <h3 style="text-align: center; margin-bottom: 5px; font-size: 25px; font-weight: 600; color: #44749D;">Pedido Atual</h3>
                        <span id="itens-pedido"></span>
                        <span id="total-pedido"></span>
                    </div>
                <button id="limpar-pedido" class="input-submit">Limpar Pedido</button>
            </div>

            <div id="porra" class="form-container-finalizar oculto" style="background-image: url(img/dinero.png);">
                <form id="form-finalizar">
                    <h3 style="text-align: center; margin-bottom: 25px; font-size: 25px; font-weight: 600; color: #44749D;">Finalizar Pedido</h3>
                    <label for="forma_pagamento" style="font-size: 20px !important" ;>Forma de Pagamento:</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="input-text" style='font-size: 25px; text-align: center;'>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="credito">Crédito</option>
                        <option value="debito">Débito</option>
                        <option value="pix">PIX</option>
                    </select>
                    <div id="valor-dinheiro-div">
                        <label for="valor_dinheiro" style="font-size: 20px !important;">Valor em Dinheiro:</label>
                        <input class="input-text" type="number" step="0.01" name="valor_dinheiro" id="valor_dinheiro">
                    </div>
                    <h3 style="font-size: 20px !important; font-weight: bold;">Descrição:</h3>
                    <textarea name="descricao_venda" id="descricao_venda" cols="80" rows="8"></textarea>
                    <br>
                    <button name="finalizar_pedido" type="submit" class="input-submit">Finalizar Pedido</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('form-cpf').addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio normal do formulário

            // Obter o valor do CPF do formulário
            const cpf = document.getElementById('cpf').value;

            // Criar um objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configurar a requisição
            xhr.open('POST', 'buscar_cliente.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Quando a requisição for completada
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Converter a resposta JSON para um objeto
                    var data = JSON.parse(xhr.responseText);

                    // Verificar o status da resposta
                    if (data.status === 'success') {
                        // Se o cliente for encontrado, mostrar os próximos elementos
                        document.getElementById('cpf_cliente').classList.add('oculto');
                        document.getElementById('busca-produto').classList.remove('oculto');
                        document.getElementById('porra').classList.remove('oculto');
                    } else {
                        // Se o cliente não for encontrado, exibir a mensagem de erro
                        alert(data.message);
                    }
                } else {
                    alert('Erro na requisição. Tente novamente mais tarde.');
                }
            };

            // Enviar a requisição com os dados do formulário
            xhr.send('cpf=' + encodeURIComponent(cpf));
        });


        let timeout = null; // Variável para evitar múltiplas requisições seguidas

        document.getElementById('produto').addEventListener('input', function() {
            clearTimeout(timeout); // Limpa o tempo anterior para evitar requisições em excesso

            const busca = this.value.trim(); // Pega o valor do input e remove espaços extras

            if (busca.length < 2) { // Evita buscas para textos muito curtos
                document.getElementById('resultados-produto').innerHTML = '';
                return;
            }

            timeout = setTimeout(() => { // Adiciona um pequeno atraso para evitar múltiplas chamadas enquanto o usuário digita rápido
                const formData = new FormData();
                formData.append('busca', busca);

                fetch('buscar_produtos.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('resultados-produto').innerHTML = data;
                    });
            }, 300); // Aguarda 300ms antes de enviar a requisição
        });



        //adicionar produto ao pedido 
        function adicionarProduto(codProd, nomeProd, precoProd) {
            const quantidade = prompt("Informe a quantidade:", 1);
            if (quantidade != null && quantidade > 0) {
                const formData = new FormData();
                formData.append('produto_id', codProd);
                formData.append('produto_nome', nomeProd);
                formData.append('produto_preco', precoProd);
                formData.append('produto_quantidade', quantidade);
                formData.append('add_produto', '1');
                fetch('sistema_venda.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        atualizarPedido(data);
                        document.getElementById('resultados-produto').innerHTML = '';
                        document.getElementById('produto').value = '';
                    });
            }
        }

        //atualizar a lista do pedido
        function atualizarPedido(pedido) {
            const itensPedido = document.getElementById('itens-pedido');
            const totalPedido = document.getElementById('total-pedido');
            itensPedido.innerHTML = '';
            let total = 0;
            pedido.forEach(item => {
                itensPedido.innerHTML += `<div style="margin: 5px;">${item.nome_prod} - R$ ${item.preco_prod} x ${item.quantidade}</div>`;
                total += item.preco_prod * item.quantidade;
            });
            totalPedido.textContent = `Total: R$ ${total.toFixed(2)}`;
        }

        document.getElementById('limpar-pedido').addEventListener('click', function(event) {
            event.preventDefault(); // Impede que a página recarregue

            fetch('limpar_pedido.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    atualizarPedido([]); // Limpa os itens do pedido na interface
                });
        });


        document.getElementById('form-finalizar').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('finalizar_pedido', '1');
            fetch('sistema_venda.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        atualizarPedido([]); // Limpa o  pedido
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
        });
    </script>
</body>

</html>