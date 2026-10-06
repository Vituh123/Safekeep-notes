-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/10/2026 às 10:24
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `safekeep_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo_criptografado` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notas`
--

INSERT INTO `notas` (`id`, `id_usuario`, `titulo`, `conteudo_criptografado`, `data_criacao`) VALUES
(3, 4, 'oi boa noite', 'Lptf7Tg/OnEJCKHjSyDA6zo6dzFwS1VNbE12S09Ec0JhU2dmTXJEQTE1K2RPZENoZmpzQzBBbW1xcEs3az0=', '2026-09-26 09:07:13'),
(4, 4, 'filhos', '/qzBMi7YiNc27vv4SxqoYDo6YjdZbkpPeE5JVE5PdkJVOURSSmNhZXlvaEs2ckZvYlNGRi95TXJERDVFST0=', '2026-09-26 10:15:50'),
(5, 4, 'vitor', '5yGsuA58SBypkro0vZYDMTo6NG5RV1B6eXJ1RTdBOHVwZHV3WFBJd0VjNU14TFZRdWhDVS9UQ3JMQkZVVT0=', '2026-09-26 10:16:08'),
(7, 12, 'teste', 'yH+jIt4QKXgj4G7O+6JTqjo6cHVMN3lDaUpBenM0Zm15LzZJL2JIZz09', '2026-10-06 08:12:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(4, 'wanessa123', 'wanessa123@gmail.com', '$2y$10$ut6gmOARB1MDZu2JTKleleW.QFLXvSZkpswI8CRURZ0GJKYwD5Mdy'),
(5, 'luna123', 'luninhachata@gmail.com', '$2y$10$TpedxoFnhaXonREfFweZGeyZsv1TcJko4VTlGTr06R.HwuFAOulTC'),
(7, 'teste1', 'teste1@gmail.com', '$2y$10$lFo.BOBavkdPANaploJuwu63vi0fBeIeCttveKOrzYVZdwuIqtNBC'),
(8, 'teste2', 'teste@gmail.com', '$2y$10$e16Dn0xaJGyIq5BLNVMW8.AkANzRHXdv08Zs0o0s5WiY9BOH.xiZW'),
(10, 'teste3', 'teste3@gmail.com', '$2y$10$oX93nz1jb15q2s/eSrhxnOVWAIuyQgYH8EYn/mcbdvBzRsTwI17CO'),
(12, 'Vitor Lucas', 'novoteste@gmail.com', '$2y$10$UsLfdYKKdrkagcvYBnaEyOlvxbSKmyUeX.D186K6AF7mG3spoOjai');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `notas`
--
ALTER TABLE `notas`
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
-- AUTO_INCREMENT de tabela `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
