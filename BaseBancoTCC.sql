-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Jun-2023 às 17:44
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alunos_semestre`
--

CREATE TABLE `tb_alunos_semestre` (
  `IDAluno` int(11) NOT NULL,
  `matricula` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `status` varchar(50) NOT NULL,
  `semestre` varchar(10) NOT NULL,
  `conceito` varchar(1) DEFAULT 'Q'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_alunos_semestre`
--

INSERT IGNORE INTO `tb_alunos_semestre` (`IDAluno`, `matricula`, `nome`, `status`, `semestre`, `conceito`) VALUES (1, 10000, 'Coordenador', '', '', 'Q');


--
-- Estrutura da tabela `tb_arquivos_importados`
--

CREATE TABLE `tb_arquivos_importados` (
  `ID` int(11) NOT NULL,
  `nomeArquivo` varchar(100) NOT NULL,
  `cargaHoraria` int(11) NOT NULL,
  `totalContabilizado` int(11) NOT NULL,
  `statusArquivo` varchar(50) NOT NULL,
  `codigoArquivo` varchar(100) NOT NULL,
  `arquivo` longblob NOT NULL,
  `IDAluno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_atividades`
--

CREATE TABLE `tb_atividades` (
  `codigo` varchar(5) NOT NULL,
  `maximoAtividade` int(11) NOT NULL,
  `maximoLimite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_atividades`
--

INSERT IGNORE INTO `tb_atividades` (`codigo`, `maximoAtividade`, `maximoLimite`) VALUES
('AE001', 90, 90),
('AE002', 90, 90),
('AE003', 15, 90),
('AE004', 20, 60),
('AE005', 15, 45),
('AE006', 30, 90),
('AE007', 15, 60),
('AE008', 30, 90),
('AE009', 30, 90),
('AE010', 30, 90),
('AE011', 30, 60),
('AE012', 90, 90),
('AE013', 90, 90),
('AE014', 30, 30),
('AE015', 90, 90),
('AE016', 5, 10),
('AE017', 90, 90);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_login`
--

CREATE TABLE `tb_login` (
  `ID` int(11) NOT NULL,
  `matricula` int(11) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `IDAluno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_login`
--

INSERT IGNORE INTO `tb_login` (`ID`, `matricula`, `senha`, `IDAluno`) VALUES (4, 10000, 'teste', 1);


--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_alunos_semestre`
--
ALTER TABLE `tb_alunos_semestre`
  ADD PRIMARY KEY (`IDAluno`);

--
-- Índices para tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDAluno` (`IDAluno`);

--
-- Índices para tabela `tb_atividades`
--
ALTER TABLE `tb_atividades`
  ADD PRIMARY KEY (`codigo`);

--
-- Índices para tabela `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDAluno` (`IDAluno`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_alunos_semestre`
--
ALTER TABLE `tb_alunos_semestre`
  MODIFY `IDAluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT de tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_arquivos_importados`
--
ALTER TABLE `tb_arquivos_importados`
  ADD CONSTRAINT `tb_arquivos_importados_ibfk_1` FOREIGN KEY (`IDAluno`) REFERENCES `tb_alunos_semestre` (`IDAluno`);

--
-- Limitadores para a tabela `tb_login`
--
ALTER TABLE `tb_login`
  ADD CONSTRAINT `tb_login_ibfk_1` FOREIGN KEY (`IDAluno`) REFERENCES `tb_alunos_semestre` (`IDAluno`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
