<?php
$conexao = mysqli_connect("localhost", "root", "", "erp");
if (!$conexao) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = $_POST['busca'] ?? ''; // O nome deve ser "busca" para combinar com o JavaScript

$sql = "SELECT * FROM produto WHERE nome_prod LIKE '%$query%' OR cod_prod LIKE '%$query%'";
$result = mysqli_query($conexao, $sql);
$produtos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $produtos[] = $row;
}

if (!empty($produtos)) {
    foreach ($produtos as $produto) {
        echo '<style>
                #teupai {
                    font-size: 15px !important;        
                    padding: 10px 15px;
                    text-decoration: none;
                    background-color: #44749D;
                    color: white;
                    border: 5px solid;
                    border-color: #5C9AC2 #2E5674 #2E5674 #5C9AC2;
                    border-radius: 20px;
                    font-size: 1rem;
                    text-align: center;
                    transition: background 0.3s, transform 0.3s;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                #teupai:hover {
                    background: #365f7d;
                    transform: scale(1.05);
                    }
            </style>';
        echo '<div id="teupai" onclick="adicionarProduto(' . $produto['cod_prod'] . ', \'' . $produto['nome_prod'] . '\', ' . $produto['preco_prod'] . ')">';
        echo 'Código: ' . $produto['cod_prod'] . ' - Nome: ' . $produto['nome_prod'] . ' - Preço: R$ ' . number_format($produto['preco_prod'], 2, ',', '.');
        echo '</div>';
    }
} else {
    echo 'Nenhum produto encontrado.';
}
