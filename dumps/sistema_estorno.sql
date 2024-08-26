-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/08/2024 às 01:54
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_estorno`
--
CREATE DATABASE IF NOT EXISTS `sistema_estorno` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_estorno`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_estorno`
--

CREATE TABLE `solicitacoes_estorno` (
  `id` int(11) NOT NULL,
  `id_transacao` int(11) NOT NULL,
  `data_solicitacao` date NOT NULL,
  `status` enum('pendente','autorizado','negado') DEFAULT 'pendente',
  `autorizado_por` int(11) DEFAULT NULL,
  `data_autorizacao` timestamp NULL DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT 0.00,
  `parcelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `solicitacoes_estorno`
--

INSERT INTO `solicitacoes_estorno` (`id`, `id_transacao`, `data_solicitacao`, `status`, `autorizado_por`, `data_autorizacao`, `valor`, `parcelas`) VALUES
(8, 5, '2024-08-14', 'autorizado', 15, '2024-08-26 20:40:43', 100.00, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_transacao` date NOT NULL,
  `parcelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `id_usuario`, `valor`, `data_transacao`, `parcelas`) VALUES
(5, 18, 100.00, '2024-08-08', 3),
(6, 16, 270.21, '2024-08-12', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_permissao` enum('usuario','administrador') DEFAULT 'usuario',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel_permissao`, `criado_em`) VALUES
(15, 'jonatan', 'jonatan@gmail.com', '123', 'administrador', '2024-08-26 18:38:19'),
(16, 'maria', 'maria@gmail.com', '123', 'usuario', '2024-08-26 18:38:40'),
(17, 'pedro', 'pedro@gmail.com', '123', 'usuario', '2024-08-26 18:39:10'),
(18, 'jose', 'jose@gmail.com', '123', 'usuario', '2024-08-26 18:39:10');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `solicitacoes_estorno`
--
ALTER TABLE `solicitacoes_estorno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_transacao` (`id_transacao`),
  ADD KEY `autorizado_por` (`autorizado_por`);

--
-- Índices de tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `solicitacoes_estorno`
--
ALTER TABLE `solicitacoes_estorno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `solicitacoes_estorno`
--
ALTER TABLE `solicitacoes_estorno`
  ADD CONSTRAINT `solicitacoes_estorno_ibfk_1` FOREIGN KEY (`id_transacao`) REFERENCES `transacoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitacoes_estorno_ibfk_2` FOREIGN KEY (`autorizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `transacoes`
--
ALTER TABLE `transacoes`
  ADD CONSTRAINT `transacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
