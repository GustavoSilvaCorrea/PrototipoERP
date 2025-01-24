-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Maio-2024 às 19:44
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `erp`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `cod_cli` int(11) NOT NULL,
  `nome_cli` varchar(50) DEFAULT NULL,
  `email_cli` varchar(50) DEFAULT NULL,
  `tel_cli` varchar(11) DEFAULT NULL,
  `cpf_cli` varchar(15) DEFAULT NULL,
  `descricao_cli` mediumtext DEFAULT NULL,
  `funcionario_cod_fun` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `compra`
--

CREATE TABLE `compra` (
  `cod_com` int(11) NOT NULL,
  `data_com` date NOT NULL,
  `valor_com` float NOT NULL,
  `item_com` varchar(55) NOT NULL,
  `qtd_com` float NOT NULL,
  `observacao_com` mediumtext DEFAULT NULL,
  `fornecedores_cod_for` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE `empresa` (
  `cod_emp` int(11) NOT NULL,
  `nome_emp` varchar(50) NOT NULL,
  `endereco_emp` varchar(60) NOT NULL,
  `cnpj_emp` varchar(14) NOT NULL,
  `logo_emp` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `empresa`
--

INSERT INTO `empresa` (`cod_emp`, `nome_emp`, `endereco_emp`, `cnpj_emp`, `logo_emp`) VALUES
(1, 'japonesicos', 'rua', '40028922', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estoque`
--

CREATE TABLE `estoque` (
  `cod_est` int(11) NOT NULL,
  `quantidade_est` float NOT NULL,
  `data_entrada_est` datetime NOT NULL,
  `data_saida_est` datetime NOT NULL,
  `compra_cod_com` int(11) NOT NULL,
  `produto_cod_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `cod_for` int(11) NOT NULL,
  `nome_for` varchar(45) NOT NULL,
  `cnpj_cpf_for` varchar(17) NOT NULL,
  `endereco_for` varchar(45) NOT NULL,
  `email_for` varchar(45) NOT NULL,
  `tel_for` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `cod_fun` int(11) NOT NULL,
  `nome_fun` varchar(50) NOT NULL,
  `nascimento_fun` date NOT NULL,
  `endereco_fun` varchar(45) NOT NULL,
  `email_fun` varchar(50) NOT NULL,
  `tel_fun` varchar(11) DEFAULT NULL,
  `funcao_fun` varchar(40) NOT NULL,
  `status_fun` varchar(1) NOT NULL,
  `login_fun` varchar(45) NOT NULL,
  `senha_fun` varchar(45) NOT NULL,
  `data_adimissao_fun` date NOT NULL,
  `salario_fun` float NOT NULL,
  `foto_fun` longtext DEFAULT NULL,
  `feedback_fun` longtext NOT NULL,
  `empresa_cod_emp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `funcionario`
--

INSERT INTO `funcionario` (`cod_fun`, `nome_fun`, `nascimento_fun`, `endereco_fun`, `email_fun`, `tel_fun`, `funcao_fun`, `status_fun`, `login_fun`, `senha_fun`, `data_adimissao_fun`, `salario_fun`, `foto_fun`, `feedback_fun`, `empresa_cod_emp`) VALUES
(2, 'administrador', '2024-05-09', 'casa', 'admin@gmail.com', NULL, 'administrador', 'A', 'admin', '1', '2024-05-09', 10000, NULL, 'funcionario dedicado, filho do chefe. oda genio.', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `cod_orc` int(11) NOT NULL,
  `lucro_bruto_orc` float NOT NULL,
  `lucro_líquido_orc` float NOT NULL,
  `fluxo_caixa_orc` float NOT NULL,
  `dispesas_orc` float NOT NULL,
  `venda_cod_venda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `cod_prod` int(11) NOT NULL,
  `nome_prod` varchar(45) NOT NULL,
  `descricao_prod` mediumtext NOT NULL,
  `medida_prod` varchar(15) NOT NULL,
  `estoque_min_prod` float NOT NULL,
  `tipo_prod` varchar(45) NOT NULL,
  `preco_prod` float NOT NULL,
  `foto_prod` longtext NOT NULL,
  `lucro_prod` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio_cliente`
--

CREATE TABLE `relatorio_cliente` (
  `cod_rel_cli` int(11) NOT NULL,
  `nome_rel_cli` varchar(45) DEFAULT NULL,
  `data_cadastro_rel_cli` varchar(45) DEFAULT NULL,
  `compras_rel_cli` int(11) DEFAULT NULL,
  `valor_compras_rel_cli` float DEFAULT NULL,
  `cliente_cod_cli` int(11) NOT NULL,
  `venda_cod_venda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio_monetario`
--

CREATE TABLE `relatorio_monetario` (
  `cod_mon` int(11) NOT NULL,
  `data_mon` date NOT NULL,
  `nr_vendas_mon` int(11) NOT NULL,
  `nr_compras_mon` int(11) NOT NULL,
  `venda_cod_venda` int(11) NOT NULL,
  `compra_cod_com` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `venda`
--

CREATE TABLE `venda` (
  `cod_venda` int(11) NOT NULL,
  `nome_cliente_vend` varchar(45) DEFAULT NULL,
  `produto_venda` varchar(45) NOT NULL,
  `data_venda` datetime NOT NULL,
  `quantidade_venda` float NOT NULL,
  `valor_total_venda` float NOT NULL,
  `status_venda` varchar(45) NOT NULL,
  `forma_pagamento_venda` varchar(45) NOT NULL,
  `custo_venda` varchar(45) NOT NULL,
  `descricao_venda` mediumtext DEFAULT NULL,
  `funcionario_cod_fun` int(11) NOT NULL,
  `estoque_cod_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cod_cli`),
  ADD KEY `funcionario_cod_fun` (`funcionario_cod_fun`);

--
-- Índices para tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`cod_com`),
  ADD KEY `fornecedores_cod_for` (`fornecedores_cod_for`);

--
-- Índices para tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`cod_emp`);

--
-- Índices para tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`cod_est`),
  ADD KEY `compra_cod_com` (`compra_cod_com`),
  ADD KEY `produto_cod_prod` (`produto_cod_prod`);

--
-- Índices para tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`cod_for`);

--
-- Índices para tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`cod_fun`),
  ADD KEY `empresa_cod_emp` (`empresa_cod_emp`);

--
-- Índices para tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`cod_orc`),
  ADD KEY `venda_cod_venda` (`venda_cod_venda`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`cod_prod`);

--
-- Índices para tabela `relatorio_cliente`
--
ALTER TABLE `relatorio_cliente`
  ADD PRIMARY KEY (`cod_rel_cli`),
  ADD KEY `cliente_cod_cli` (`cliente_cod_cli`),
  ADD KEY `venda_cod_venda` (`venda_cod_venda`);

--
-- Índices para tabela `relatorio_monetario`
--
ALTER TABLE `relatorio_monetario`
  ADD PRIMARY KEY (`cod_mon`),
  ADD KEY `venda_cod_venda` (`venda_cod_venda`),
  ADD KEY `compra_cod_com` (`compra_cod_com`);

--
-- Índices para tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`cod_venda`),
  ADD KEY `funcionario_cod_fun` (`funcionario_cod_fun`),
  ADD KEY `estoque_cod_est` (`estoque_cod_est`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cod_cli` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `cod_com` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `cod_emp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `cod_est` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `cod_for` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `cod_fun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `cod_orc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `cod_prod` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorio_cliente`
--
ALTER TABLE `relatorio_cliente`
  MODIFY `cod_rel_cli` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorio_monetario`
--
ALTER TABLE `relatorio_monetario`
  MODIFY `cod_mon` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `cod_venda` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`funcionario_cod_fun`) REFERENCES `funcionario` (`cod_fun`);

--
-- Limitadores para a tabela `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`fornecedores_cod_for`) REFERENCES `fornecedores` (`cod_for`);

--
-- Limitadores para a tabela `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`compra_cod_com`) REFERENCES `compra` (`cod_com`),
  ADD CONSTRAINT `estoque_ibfk_2` FOREIGN KEY (`produto_cod_prod`) REFERENCES `produto` (`cod_prod`);

--
-- Limitadores para a tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`empresa_cod_emp`) REFERENCES `empresa` (`cod_emp`);

--
-- Limitadores para a tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD CONSTRAINT `orcamento_ibfk_1` FOREIGN KEY (`venda_cod_venda`) REFERENCES `venda` (`cod_venda`);

--
-- Limitadores para a tabela `relatorio_cliente`
--
ALTER TABLE `relatorio_cliente`
  ADD CONSTRAINT `relatorio_cliente_ibfk_1` FOREIGN KEY (`cliente_cod_cli`) REFERENCES `cliente` (`cod_cli`),
  ADD CONSTRAINT `relatorio_cliente_ibfk_2` FOREIGN KEY (`venda_cod_venda`) REFERENCES `venda` (`cod_venda`);

--
-- Limitadores para a tabela `relatorio_monetario`
--
ALTER TABLE `relatorio_monetario`
  ADD CONSTRAINT `relatorio_monetario_ibfk_1` FOREIGN KEY (`venda_cod_venda`) REFERENCES `venda` (`cod_venda`),
  ADD CONSTRAINT `relatorio_monetario_ibfk_2` FOREIGN KEY (`compra_cod_com`) REFERENCES `compra` (`cod_com`);

--
-- Limitadores para a tabela `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`funcionario_cod_fun`) REFERENCES `funcionario` (`cod_fun`),
  ADD CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`estoque_cod_est`) REFERENCES `estoque` (`cod_est`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
