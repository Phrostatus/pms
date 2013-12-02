-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 13-Nov-2013 às 01:25
-- Versão do servidor: 5.5.32
-- versão do PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `pms-carpool`
--
CREATE DATABASE IF NOT EXISTS `pms-carpool` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pms-carpool`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `condutor`
--

CREATE TABLE IF NOT EXISTS `condutor` (
  `utilizador_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`utilizador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `condutor`
--

INSERT INTO `condutor` (`utilizador_id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `local`
--

CREATE TABLE IF NOT EXISTS `local` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conselho` varchar(45) NOT NULL,
  `freguesia` varchar(45) NOT NULL,
  `local` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Extraindo dados da tabela `local`
--

INSERT INTO `local` (`id`, `conselho`, `freguesia`, `local`) VALUES
(1, 'Funchal', 'São Roque', 'Igreja'),
(2, 'Funchal', 'Monte', 'Igreja'),
(3, 'Funchal', 'Imaculado Coração de Maria', 'Igreja'),
(4, 'Funchal', 'São Pedro', 'Igreja'),
(5, 'Funchal', 'São gonçalo', 'Igreja'),
(6, 'Funchal', 'São Martinho', 'Igreja'),
(7, 'Funchal', 'Santa Maria Maior', 'Igreja'),
(8, 'Funchal', 'Santa Luzia', 'Igreja'),
(9, 'Funchal', 'Sé (Funchal)', 'Dolce Vita'),
(10, 'Funchal', 'Sé (Funchal)', 'Jardim de São Francisco'),
(11, 'Funchal', 'Sé (Funchal)', 'Parque de Santa Catarina'),
(12, 'Funchal', 'Sé (Funchal)', 'Escola Secundária Francisco Franco'),
(13, 'Funchal', 'Sé (Funchal)', 'Castelo de São Lourenço'),
(14, 'Funchal', 'Sé (Funchal)', 'Museu da Eletricidade'),
(15, 'Funchal', 'Sé (Funchal)', 'Mercado dos Lavradores'),
(16, 'Funchal', 'Sé (Funchal)', 'Anadia'),
(17, 'Funchal', 'Sé (Funchal)', 'Marina Shopping'),
(18, 'Funchal', 'Sé (Funchal)', 'Dolce Vita'),
(19, 'Funchal', 'Sé (Funchal)', 'Galerias de São Lourenço'),
(20, 'Funchal', 'Sé (Funchal)', 'Hospital'),
(21, 'Funchal', 'São Martinho', 'Lido'),
(22, 'Funchal', 'São Martinho', 'Ponta Gorda'),
(23, 'Funchal', 'São Martinho', 'Praia Formosa'),
(24, 'Funchal', 'São Martinho', 'Forúm Madeira'),
(25, 'Funchal', 'Sé (Funchal)', 'Paragem SAM'),
(26, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Igreja'),
(27, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Correios CTT'),
(28, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Mercado'),
(29, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Centro Cívico'),
(30, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Escola Primária'),
(31, 'Camâra de Lobos', 'Estreito de Camâra de Lobos', 'Bar Viola'),
(32, 'Camâra de Lobos', 'Camâra de Lobos', 'Biblioteca'),
(33, 'Camâra de Lobos', 'Camâra de Lobos', 'Igreja'),
(34, 'Camâra de Lobos', 'Camâra de Lobos', 'Pingo Doce'),
(35, 'Camâra de Lobos', 'Camâra de Lobos', 'Mercado'),
(36, 'Camâra de Lobos', 'Camâra de Lobos', 'Modelo'),
(37, 'Camâra de Lobos', 'Camâra de Lobos', 'Vila do Peixe'),
(38, 'Camâra de Lobos', 'Camâra de Lobos', 'Correios CTT'),
(39, 'Camâra de Lobos', 'Camâra de Lobos', 'Parque Estacionamento'),
(40, 'Machico', 'Machico', 'Central de Autocarros'),
(41, 'Machico', 'Machico', 'Flor do Campo'),
(42, 'Machico', 'Machico', 'Igreja Matriz'),
(43, 'Machico', 'Machico', 'Igreja Caramanchão'),
(44, 'Machico', 'Machico', 'Igreja Maroços'),
(45, 'Machico', 'Machico', 'Igreja Ribeira Seca'),
(46, 'Machico', 'Machico', 'Casa do Povo'),
(47, 'Machico', 'Caniçal', 'Igreja Matriz'),
(48, 'Machico', 'Água de Pena', 'Parque Desportivo'),
(49, 'Machico', 'Santo da Serra', 'Enotel Golf Santo da Serra');

-- --------------------------------------------------------

--
-- Estrutura da tabela `local_has_condutor`
--

CREATE TABLE IF NOT EXISTS `local_has_condutor` (
  `local_id` int(10) unsigned NOT NULL,
  `condutor_utilizador_id` int(10) unsigned NOT NULL,
  `hora` time DEFAULT NULL,
  `tolerancia` time DEFAULT NULL,
  PRIMARY KEY (`local_id`,`condutor_utilizador_id`),
  KEY `fk_local_has_condutor_condutor1_idx` (`condutor_utilizador_id`),
  KEY `fk_local_has_condutor_local1_idx` (`local_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacao`
--

CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` int(10) unsigned NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `lida` binary(1) NOT NULL,
  `emissor_id` int(10) unsigned NOT NULL,
  `recetor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notificacao_utilizador1_idx` (`emissor_id`),
  KEY `fk_notificacao_utilizador2_idx` (`recetor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `passageiro`
--

CREATE TABLE IF NOT EXISTS `passageiro` (
  `utilizador_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`utilizador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

CREATE TABLE IF NOT EXISTS `utilizador` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telemovel` int(10) unsigned NOT NULL,
  `morada` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telemovel_UNIQUE` (`telemovel`),
  UNIQUE KEY `mail_UNIQUE` (`mail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id`, `password`, `salt`, `nome`, `mail`, `telemovel`, `morada`) VALUES
(1, 'ac581c2ce2c4a176796986c50629bc01a3ffbb615c1d1b89e4bd5d1009fd005def6d356290bf02960be62fca360cc7eed9d484cc0c2aba2e1e58a0099c9411a5', '1234', 'miguel', 'para_cenas1@hotmail.com', 917754700, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem`
--

CREATE TABLE IF NOT EXISTS `viagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avaliacao_condutor` int(10) unsigned DEFAULT NULL,
  `avaliacao_passageiro` int(10) unsigned DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `condutor_utilizador_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_viagem_condutor1_idx` (`condutor_utilizador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem_has_local`
--

CREATE TABLE IF NOT EXISTS `viagem_has_local` (
  `viagem_id` int(11) NOT NULL,
  `locail_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`viagem_id`,`locail_id`),
  KEY `fk_viagem_has_locail_locail1_idx` (`locail_id`),
  KEY `fk_viagem_has_locail_viagem1_idx` (`viagem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem_passageiro`
--

CREATE TABLE IF NOT EXISTS `viagem_passageiro` (
  `viagem_id` int(11) NOT NULL,
  `passageiro_utilizador_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`viagem_id`,`passageiro_utilizador_id`),
  KEY `fk_viagem_has_passageiro_passageiro1_idx` (`passageiro_utilizador_id`),
  KEY `fk_viagem_has_passageiro_viagem1_idx` (`viagem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `condutor`
--
ALTER TABLE `condutor`
  ADD CONSTRAINT `fk_condutor_utilizador` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `local_has_condutor`
--
ALTER TABLE `local_has_condutor`
  ADD CONSTRAINT `fk_local_has_condutor_condutor1` FOREIGN KEY (`condutor_utilizador_id`) REFERENCES `condutor` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_local_has_condutor_local1` FOREIGN KEY (`local_id`) REFERENCES `local` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `notificacao`
--
ALTER TABLE `notificacao`
  ADD CONSTRAINT `fk_notificacao_utilizador1` FOREIGN KEY (`emissor_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificacao_utilizador2` FOREIGN KEY (`recetor_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `passageiro`
--
ALTER TABLE `passageiro`
  ADD CONSTRAINT `fk_passageiro_utilizador1` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `fk_viagem_condutor1` FOREIGN KEY (`condutor_utilizador_id`) REFERENCES `condutor` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `viagem_has_local`
--
ALTER TABLE `viagem_has_local`
  ADD CONSTRAINT `fk_viagem_has_locail_locail1` FOREIGN KEY (`locail_id`) REFERENCES `local` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_has_locail_viagem1` FOREIGN KEY (`viagem_id`) REFERENCES `viagem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `viagem_passageiro`
--
ALTER TABLE `viagem_passageiro`
  ADD CONSTRAINT `fk_viagem_has_passageiro_passageiro1` FOREIGN KEY (`passageiro_utilizador_id`) REFERENCES `passageiro` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_has_passageiro_viagem1` FOREIGN KEY (`viagem_id`) REFERENCES `viagem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
