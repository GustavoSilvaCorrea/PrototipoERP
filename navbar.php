<?php
$conectar = mysqli_connect("localhost", "root", "", "erp");

echo "<style> #fun{ display:none }</style>";
include "valida_login.php";

$permissao_fun = explode(",", $linha["permissao_fun"]);
?>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
<style>
    /* Estilo para o dropdown */
    .navbar-inverse .nav .dropdown-menu {
        background-color: #333;
    }

    .navbar-inverse .nav .dropdown-menu>li>a {
        color: #dcdcdc;
    }

    /* Estilo para o dropdown */
    .navbar-inverse .nav .dropdown-menu {
        background-color: #333;
    }

    .navbar-inverse .nav .dropdown-menu>li>a:hover,
    .navbar-inverse .nav .dropdown-menu>li>a:focus {
        color: #ffffff;
        background-color: #080808;
    }

    .navbar-inverse .nav .dropdown-menu>li>a {
        color: #dcdcdc;
    }

    /* Mostrar dropdown ao passar o mouse */
    .navbar-nav>li.dropdown:hover .dropdown-menu {
        display: block;
    }

    .navbar-inverse .nav .dropdown-menu>li>a:hover,
    .navbar-inverse .nav .dropdown-menu>li>a:focus {
        color: #ffffff;
        background-color: #080808;
    }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    menu,
    section,
    summary {
        display: grid;
    }

    /* Mostrar dropdown ao passar o mouse */
    .navbar-nav>li.dropdown:hover .dropdown-menu {
        display: block;
    }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    menu,
    section,
    summary {
        display: grid;
    }
</style>
<nav class='navbar navbar-inverse'>
    <div class='container-fluid'>
        
        <ul class='nav navbar-nav'>
            <li class='active'><a href='main_page.php'>Home</a></li>

            <?php if (in_array('relatorios', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='relatorios.php'>Relatórios
                        <span class='caret'></span></a>
                    <ul class='dropdown-menu'>
                        <li><a href='cadastro_relatorios.php'>Escrever relatório</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (in_array('financeiro', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='financeiro.php'>Financeiro
                        <span class='caret'></span></a>
                </li>
            <?php } ?>

            <?php if (in_array('vendas', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='vendas.php'>Vendas
                        <span class='caret'></span></a>
                    <ul class='dropdown-menu'>
                        <li><a href='sistema_venda.php'>Realizar vendas</a></li>
                        <li><a href='lista_cli.php'>Clientes</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (in_array('compra', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='compra.php'>Compras
                        <span class='caret'></span></a>
                    <ul class='dropdown-menu'>
                        <li><a href='fornecedores.php'>Fornecedores</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (in_array('estoque', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='estoque.php'>Estoque
                        <span class='caret'></span></a>
                    <ul class='dropdown-menu'>
                        <li><a href='cadastro_produtos.php'>Cadastrar Produtos</a></li>
                        <li><a href='editar_produto.php'>Alterar Produtos</a></li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (in_array('funcionarios', $permissao_fun)) { ?>
                <li class='dropdown'>
                    <a class='dropdown-toggle' href='funcionarios.php'>Funcionários
                        <span class='caret'></span></a>
                </li>
            <?php } ?>

            <li class='dropdown'>
                <a class='dropdown-toggle' href='logout.php'>Logout ▾</a>
            </li>
        </ul>
    </div>
</nav>
