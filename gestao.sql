CREATE DATABASE IF NOT EXISTS `gestao` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `gestao`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/11/2026 às 21:02
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
-- Banco de dados: `gestao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` int(11) NOT NULL,
  `empresaId` int(11) NOT NULL,
  `candidatoId` int(11) NOT NULL,
  `nota` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `possuiRampa` tinyint(1) NOT NULL,
  `possuiBanheiroAdaptado` tinyint(1) NOT NULL,
  `possuiSinalizacaoBraile` tinyint(1) NOT NULL,
  `possuiAcessibilidadeDigital` tinyint(1) NOT NULL,
  `dataAvaliacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `empresaId`, `candidatoId`, `nota`, `comentario`, `possuiRampa`, `possuiBanheiroAdaptado`, `possuiSinalizacaoBraile`, `possuiAcessibilidadeDigital`, `dataAvaliacao`) VALUES
(1, 17, 1, 1, 'Pessoas ignorantes!', 0, 0, 0, 0, '2026-11-04 20:44:38');

-- --------------------------------------------------------

--
-- Estrutura para tabela `candidato`
--

CREATE TABLE `candidato` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `formatoCurriculo` varchar(50) DEFAULT NULL,
  `cidade` varchar(180) DEFAULT NULL,
  `sobre` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `candidato`
--

INSERT INTO `candidato` (`id`, `nome`, `email`, `senha`, `telefone`, `formatoCurriculo`) VALUES
(1, 'Victor', 'joaoVictor@gmail.com', '$2y$10$CahgMMOl4fBqBQTndLoGCeJil3td4K5p52alK23A1STKMNyraM8ne', '1234', 'Texto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `cidade` varchar(180) DEFAULT NULL,
  `areaAtuacao` varchar(120) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `email`, `senha`, `telefone`) VALUES
(17, 'Arasuper', 'arasuper', '$2y$10$AGlqQkFI6miFG3OnZpUWVutEM6sm6paZ74zCTdSgacXb5bz9Uj4C6', '1234');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresaId` (`empresaId`),
  ADD KEY `candidatoId` (`candidatoId`);

--
-- Índices de tabela `candidato`
--
ALTER TABLE `candidato`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `candidato`
--
ALTER TABLE `candidato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`empresaId`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `avaliacao_ibfk_2` FOREIGN KEY (`candidatoId`) REFERENCES `candidato` (`id`);

-- Recursos necessários ao IncluiTrabalho (novas funcionalidades)
CREATE TABLE IF NOT EXISTS `vaga` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `empresaId` INT NOT NULL,
 `titulo` VARCHAR(180) NOT NULL,
 `descricao` TEXT NOT NULL,
 `localizacao` VARCHAR(180) DEFAULT NULL,
 `modalidade` ENUM('Presencial','Híbrido','Remoto') NOT NULL DEFAULT 'Presencial',
 `tipoContrato` VARCHAR(80) DEFAULT NULL,
 `salario` DECIMAL(10,2) DEFAULT NULL,
 `status` ENUM('Aberta','Encerrada') NOT NULL DEFAULT 'Aberta',
 `dataPublicacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `idx_vaga_empresa` (`empresaId`),
 CONSTRAINT `fk_vaga_empresa` FOREIGN KEY (`empresaId`) REFERENCES `empresa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `recurso_acessibilidade` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `nome` VARCHAR(120) NOT NULL,
 `descricao` TEXT DEFAULT NULL,
 PRIMARY KEY (`id`), UNIQUE KEY `uq_recurso_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vaga_recurso` (
 `vagaId` INT NOT NULL,
 `recursoId` INT NOT NULL,
 PRIMARY KEY (`vagaId`,`recursoId`),
 CONSTRAINT `fk_vr_vaga` FOREIGN KEY (`vagaId`) REFERENCES `vaga` (`id`) ON DELETE CASCADE,
 CONSTRAINT `fk_vr_recurso` FOREIGN KEY (`recursoId`) REFERENCES `recurso_acessibilidade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `candidato_recurso` (
 `candidatoId` INT NOT NULL,
 `recursoId` INT NOT NULL,
 PRIMARY KEY (`candidatoId`,`recursoId`),
 CONSTRAINT `fk_cr_candidato` FOREIGN KEY (`candidatoId`) REFERENCES `candidato` (`id`) ON DELETE CASCADE,
 CONSTRAINT `fk_cr_recurso` FOREIGN KEY (`recursoId`) REFERENCES `recurso_acessibilidade` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `candidatura` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `vagaId` INT NOT NULL,
 `candidatoId` INT NOT NULL,
 `status` ENUM('Enviada','Em análise','Aprovada','Rejeitada') NOT NULL DEFAULT 'Enviada',
 `dataCandidatura` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `uq_candidatura` (`vagaId`,`candidatoId`),
 CONSTRAINT `fk_candidatura_vaga` FOREIGN KEY (`vagaId`) REFERENCES `vaga` (`id`) ON DELETE CASCADE,
 CONSTRAINT `fk_candidatura_candidato` FOREIGN KEY (`candidatoId`) REFERENCES `candidato` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `recurso_acessibilidade` (`nome`,`descricao`) VALUES
 ('Trabalho remoto','Possibilidade de executar as atividades remotamente'),
 ('Acesso sem barreiras físicas','Circulação acessível no ambiente de trabalho'),
 ('Materiais digitais acessíveis','Conteúdos compatíveis com tecnologias assistivas'),
 ('Intérprete de Libras','Apoio de interpretação em Libras'),
 ('Banheiro adaptado','Sanitários acessíveis'),
 ('Sinalização em braile','Identificação tátil e em braile'),
 ('Tecnologia assistiva','Equipamentos ou softwares de apoio'),
 ('Horário flexível','Flexibilidade de horário quando disponível');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
