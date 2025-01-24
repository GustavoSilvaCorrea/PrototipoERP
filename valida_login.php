<?php

if (isset($_SESSION["nome_fun"])) {

    $conectar = mysqli_connect("localhost", "root", "", "erp");

    if (!$conectar) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    $nome = $_SESSION["nome_fun"];
    $sql_consulta = "SELECT foto_fun, permissao_fun, funcao_fun FROM funcionario WHERE nome_fun = '$nome'";

    $resultado_consulta = mysqli_query($conectar, $sql_consulta);

    if (mysqli_num_rows($resultado_consulta) > 0) {
        $linha = mysqli_fetch_assoc($resultado_consulta);
        $foto = $linha["foto_fun"];
        $permissao = $linha["permissao_fun"];
        $_SESSION["funcao_fun"] = $linha["funcao_fun"];

        // Pegando a página atual sem a extensão .php
        $pagina_atual = basename($_SERVER['PHP_SELF'], ".php");
        

        // Convertendo a string de permissões em um array
        $paginas_permitidas = explode(',', $permissao);

        // Adicionando páginas derivadas
        $paginas_derivadas = array(
            'relatorios' => array('cadastro_relatorios', 'editar_relatorio'),
            'vendas' => array('sistema_venda', 'lista_cli', 'detalhe_venda'),
            'compra' => array('fornecedores', 'editar_compra', 'editar_fornecedor'),
            'estoque' => array('produtos','cadastro_produtos', 'editar_produto'),
            'funcionarios' => array('editar_funcionario')
            // Adicione outras permissões derivadas aqui conforme necessário
        );

        // Adicionando as páginas derivadas ao array de páginas permitidas
        foreach ($paginas_derivadas as $chave => $derivadas) {
            if (in_array($chave, $paginas_permitidas)) {
                $paginas_permitidas = array_merge($paginas_permitidas, $derivadas);
            }
        }

        // Adicionando main_page à lista de páginas permitidas
        $paginas_permitidas[] = 'main_page';

        // Verificando se a página atual está no array de páginas permitidas
        if (!in_array($pagina_atual, $paginas_permitidas)) {
            echo "<script>alert('ACESSO NEGADO');</script>";
            echo "<script>location.href = 'index.php';</script>";
            exit();
        }

    } else {
        exit('Usuário não encontrado');
    }

    mysqli_close($conectar);
} else {
    echo "<script>alert('Você não está logado!!!');</script>";
    echo "<script>location.href = 'index.php';</script>";
}
