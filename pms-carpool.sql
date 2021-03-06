-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 24-Dez-2013 às 13:17
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
-- Estrutura da tabela `concelho`
--

DROP TABLE IF EXISTS `concelho`;
CREATE TABLE IF NOT EXISTS `concelho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `concelho`
--

INSERT INTO `concelho` (`id`, `nome`) VALUES
(1, 'Funchal'),
(2, 'Câmara de Lobos'),
(3, 'Machico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `condutor`
--

DROP TABLE IF EXISTS `condutor`;
CREATE TABLE IF NOT EXISTS `condutor` (
  `utilizador_id` int(10) unsigned NOT NULL,
  `rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`utilizador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `condutor`
--

INSERT INTO `condutor` (`utilizador_id`, `rating`) VALUES
(1, 0),
(5, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `freguesia`
--

DROP TABLE IF EXISTS `freguesia`;
CREATE TABLE IF NOT EXISTS `freguesia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concelho_id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_freguesia_concelho1_idx` (`concelho_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Extraindo dados da tabela `freguesia`
--

INSERT INTO `freguesia` (`id`, `concelho_id`, `nome`) VALUES
(1, 1, 'São Roque'),
(2, 1, 'Monte'),
(3, 1, 'Imaculado Coração de Maria'),
(4, 1, 'São Pedro'),
(5, 1, 'São Gonçalo'),
(6, 1, 'São Martinho'),
(7, 1, 'Santa Maria Maior'),
(8, 1, 'Santa Luzia'),
(9, 1, 'Sé (Funchal)'),
(10, 2, 'Estreito'),
(11, 2, 'Câmara de Lobos'),
(12, 3, 'Machico'),
(13, 3, 'Caniçal'),
(14, 3, 'Água de Pena'),
(15, 3, 'Santo da Serra');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itinerario`
--

DROP TABLE IF EXISTS `itinerario`;
CREATE TABLE IF NOT EXISTS `itinerario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `condutor_utilizador_id` int(10) unsigned NOT NULL,
  `dia` varchar(10) NOT NULL,
  `lugares_livres` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_local_has_condutor_condutor1_idx` (`condutor_utilizador_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `itinerario`
--

INSERT INTO `itinerario` (`id`, `condutor_utilizador_id`, `dia`, `lugares_livres`, `nome`) VALUES
(1, 1, 'Quarta', 2, 'Funchal- Machico'),
(2, 1, 'Segunda', 3, 'Funchal'),
(5, 1, 'Terça', 0, 'União'),
(6, 1, 'Sábado', 2, 'Casa-Trabalho'),
(7, 5, 'Segunda', 0, 'Minha Zona');

-- --------------------------------------------------------

--
-- Estrutura da tabela `itinerario_has_local`
--

DROP TABLE IF EXISTS `itinerario_has_local`;
CREATE TABLE IF NOT EXISTS `itinerario_has_local` (
  `itinerario_id` int(11) NOT NULL,
  `local_id` int(10) unsigned NOT NULL,
  `hora` time NOT NULL,
  `tolerancia` int(11) NOT NULL,
  PRIMARY KEY (`itinerario_id`,`local_id`),
  KEY `fk_itinerario_has_local_local1_idx` (`local_id`),
  KEY `fk_itinerario_has_local_itinerario1_idx` (`itinerario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `itinerario_has_local`
--

INSERT INTO `itinerario_has_local` (`itinerario_id`, `local_id`, `hora`, `tolerancia`) VALUES
(1, 1, '11:50:00', 20),
(1, 2, '11:30:00', 5),
(1, 3, '12:00:00', 5),
(1, 15, '12:45:00', 10),
(2, 3, '08:00:00', 15),
(2, 47, '02:00:00', 15),
(5, 32, '03:00:00', 15),
(5, 38, '03:30:00', 15),
(5, 48, '05:00:00', 15),
(7, 32, '00:00:00', 15),
(7, 38, '00:30:00', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `local`
--

DROP TABLE IF EXISTS `local`;
CREATE TABLE IF NOT EXISTS `local` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `freguesia_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_local_freguesia1` (`freguesia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

--
-- Extraindo dados da tabela `local`
--

INSERT INTO `local` (`id`, `freguesia_id`, `nome`) VALUES
(1, 1, 'Igreja'),
(2, 2, 'Igreja'),
(3, 3, 'Igreja'),
(4, 4, 'Igreja'),
(5, 5, 'Igreja'),
(6, 6, 'Igreja'),
(7, 7, 'Igreja'),
(8, 6, 'Lido'),
(9, 6, 'Ponta Gorda'),
(10, 6, 'Forúm Madeira'),
(11, 7, 'Igreja'),
(12, 8, 'Igreja'),
(13, 9, 'Dolce Vita'),
(14, 9, 'Jardim de São Francisco'),
(15, 9, 'Parque de Sta Catarina'),
(16, 9, 'Escola Secundária Francisco Franco'),
(17, 9, 'Castelo de São Lourenço'),
(18, 9, 'Museu da Eletricidade'),
(19, 9, 'Mercado dos Lavradores'),
(20, 9, 'Anadia'),
(21, 9, 'Marina Shopping'),
(22, 9, 'Dolce Vita'),
(23, 9, 'Galerias de São Lourenço'),
(24, 9, 'Hospital'),
(25, 9, 'Paragem SAM'),
(26, 10, 'Igreja'),
(27, 10, 'Correios CTT'),
(28, 10, 'Mercado'),
(29, 10, 'Centro Cívico'),
(30, 10, 'Escola Primária'),
(31, 10, 'Bar Viola'),
(32, 11, 'Biblioteca'),
(33, 11, 'Igreja'),
(34, 11, 'Pingo Doce'),
(35, 11, 'Mercado'),
(36, 11, 'Modelo'),
(37, 11, 'Vila do Peixe'),
(38, 11, 'Correios CTT'),
(39, 11, 'Parque Estacionamento'),
(40, 12, 'Central de Autocarros'),
(41, 12, 'Flor do Campo'),
(42, 12, 'Igreja Matriz'),
(43, 12, 'Igreja Caramanch'),
(44, 12, 'Igreja Maro'),
(45, 12, 'Igreja Ribeira Seca'),
(46, 12, 'Casa do Povo'),
(47, 13, 'Igreja Matriz'),
(48, 14, 'Parque Desportivo'),
(49, 15, 'Enotel Golf Santo da Serra');

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacao`
--

DROP TABLE IF EXISTS `notificacao`;
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` int(10) unsigned NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `lida` binary(1) NOT NULL,
  `emissor_id` int(10) unsigned NOT NULL,
  `recetor_id` int(10) unsigned NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notificacao_utilizador1_idx` (`emissor_id`),
  KEY `fk_notificacao_utilizador2_idx` (`recetor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `passageiro`
--

DROP TABLE IF EXISTS `passageiro`;
CREATE TABLE IF NOT EXISTS `passageiro` (
  `utilizador_id` int(10) unsigned NOT NULL,
  `rating` float DEFAULT '0',
  PRIMARY KEY (`utilizador_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `passageiro`
--

INSERT INTO `passageiro` (`utilizador_id`, `rating`) VALUES
(2, 0),
(3, 0),
(4, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

DROP TABLE IF EXISTS `utilizador`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`id`, `password`, `salt`, `nome`, `mail`, `telemovel`, `morada`) VALUES
(1, 'de0581a85c82d55004c0cfcae19e16041d42e4014c566eae7d82bbe7a1d95af35788860f46c5df78af3e870f00beb5711dcd982a561c5d0d476e5f8aa1ab93f7', '1˜ø°LÎœ$ö¬Íß®©·áÿ%‘Øý˜a8F?èMÒ²r—öú§ÑH=eˆÔYÕhº€ü¢RB¾ ', 'Teste_condutor', 'condutor@c.c', 123456789, ''),
(2, 'd6b818bb52e528291424a2f0728a6943343129d9c83397a882c94a19bc2f85aa3b15b3a212356cb07b19ac72308be940bb3db9fd04e126e2a55269b70a712111', 'í|ã¶\0]ÙºÞêþÉå&ˆn`IüX}[4y GU9eZŽŽ½9ÝHí¿ûÈëØ¤o]õ8ñXµzÔ=—V', 'Teste_passageiro', 'passageiro@p.p', 213456789, ''),
(3, 'c8bd89430924cef566003a6d511a862cc6ca622026998037870b38df42d056a51b85e7a812c0c029df359287d47c02c57d45df2d47537bc0437e5f7a290a5fb4', 'pg0+±µÊQJ<ƒÓ\0ú¨ú~ü1Ð&?×É$£ßžo=ú±ãáQ!ùìö<9¨,#aþ*aK\rÝ¥~LÜ', 'Francisco', 'passageiro1@p.p', 961212234, 'Estreito'),
(4, '85fc2726c101c78b3a85b563141d68d3706720caed4ea5de5cb015645bda1074f36648b7322c0baddfba162dd2d9794b57a3ee0229fd3e9e93918b0d6bd08aa5', 'ý“bvõÝ<¥;“¾A‡eýsYÆ­?eî7…MœÖ8ˆDvç­ÒOµ/ÿšI\ZT	½Ë2Ï’·Ó·Ú-', 'Tiago', 'passageiro2@p.p', 912342455, 'Machico'),
(5, '7c93432cf83ff23bcb8d805b99da1fa45d6346b51c33c0acf560283ba9a446a026180e5a9af08f526b69ee0b25cb4a94f4ed34fb5d157b453357d49302f25780', ';ºÐÏùˆ¢vŸëÍÇ¹5]* £`å2˜ ªfæ¶Ú¶´¿=][fHdl\0‰719W‘xLÅífü¾ÕGÔê9Ø', 'JOSE', 'condutor1@c.c', 971222222, 'Funchal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem`
--

DROP TABLE IF EXISTS `viagem`;
CREATE TABLE IF NOT EXISTS `viagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itinerario_id` int(11) NOT NULL,
  `avaliacao_condutor` float unsigned DEFAULT '0',
  `condutor_utilizador_id` int(10) unsigned NOT NULL,
  `inicio` varchar(30) NOT NULL,
  `fim` varchar(30) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_viagem_condutor1_idx` (`condutor_utilizador_id`),
  KEY `fk_viagem_itinerario1_idx` (`itinerario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `viagem`
--

INSERT INTO `viagem` (`id`, `itinerario_id`, `avaliacao_condutor`, `condutor_utilizador_id`, `inicio`, `fim`, `data`) VALUES
(2, 5, 0, 1, '32', '38', '2013-12-17 21:44:48'),
(7, 5, 0, 1, '38', '48', '2013-12-18 12:26:34'),
(8, 7, 0, 5, '32', '38', '2013-12-19 12:15:01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `viagem_passageiro`
--

DROP TABLE IF EXISTS `viagem_passageiro`;
CREATE TABLE IF NOT EXISTS `viagem_passageiro` (
  `viagem_id` int(11) NOT NULL,
  `passageiro_utilizador_id` int(10) unsigned NOT NULL,
  `avaliacao_passageiro` float NOT NULL DEFAULT '-1',
  PRIMARY KEY (`viagem_id`,`passageiro_utilizador_id`),
  KEY `fk_viagem_has_passageiro_passageiro1_idx` (`passageiro_utilizador_id`),
  KEY `fk_viagem_has_passageiro_viagem1_idx` (`viagem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `viagem_passageiro`
--

INSERT INTO `viagem_passageiro` (`viagem_id`, `passageiro_utilizador_id`, `avaliacao_passageiro`) VALUES
(2, 2, -1),
(2, 3, -1),
(7, 2, -1),
(7, 3, -1),
(8, 3, -1);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `condutor`
--
ALTER TABLE `condutor`
  ADD CONSTRAINT `fk_condutor_utilizador` FOREIGN KEY (`utilizador_id`) REFERENCES `utilizador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `freguesia`
--
ALTER TABLE `freguesia`
  ADD CONSTRAINT `fk_freguesia_concelho1` FOREIGN KEY (`concelho_id`) REFERENCES `concelho` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `itinerario`
--
ALTER TABLE `itinerario`
  ADD CONSTRAINT `fk_local_has_condutor_condutor1` FOREIGN KEY (`condutor_utilizador_id`) REFERENCES `condutor` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `itinerario_has_local`
--
ALTER TABLE `itinerario_has_local`
  ADD CONSTRAINT `fk_itinerario_has_local_itinerario1` FOREIGN KEY (`itinerario_id`) REFERENCES `itinerario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_itinerario_has_local_local1` FOREIGN KEY (`local_id`) REFERENCES `local` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `local`
--
ALTER TABLE `local`
  ADD CONSTRAINT `fk_local_freguesia1` FOREIGN KEY (`freguesia_id`) REFERENCES `freguesia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_local_viagem_freguesia1` FOREIGN KEY (`freguesia_id`) REFERENCES `freguesia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_viagem_condutor1` FOREIGN KEY (`condutor_utilizador_id`) REFERENCES `condutor` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_itinerario1` FOREIGN KEY (`itinerario_id`) REFERENCES `itinerario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `viagem_passageiro`
--
ALTER TABLE `viagem_passageiro`
  ADD CONSTRAINT `fk_viagem_has_passageiro_passageiro1` FOREIGN KEY (`passageiro_utilizador_id`) REFERENCES `passageiro` (`utilizador_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_viagem_has_passageiro_viagem1` FOREIGN KEY (`viagem_id`) REFERENCES `viagem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
