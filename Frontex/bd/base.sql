-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando dados para a tabela base.banners: ~3 rows (aproximadamente)
INSERT IGNORE INTO `banners` (`ID`, `nome`, `img`, `descricao`, `ativo`, `ID_empresa`) VALUES
	(42, 'Banner1', '../../gg/resources/uploads/banners/6713ca367f6ac_hand-drawn-colored-banner-with-sweets-cakes-vector-23259121.jpg', 'Teste', 1, 1),
	(43, 'Banner2', '../../gg/resources/uploads/banners/66ce88066e817_banner-de-massa-caseira-fresca_23-2149252891.jpg', 'Teste', 1, 1),
	(50, 'Banner3', '../../gg/resources/uploads/banners/66ce880c23844_modelo-de-banner-horizontal-de-fabrica-de-doces_23-2149007490.jpg', 'Teste', 1, 1);

-- Copiando dados para a tabela base.carrinho: ~5 rows (aproximadamente)
INSERT IGNORE INTO `carrinho` (`ID`, `ID_usuario`, `ID_empresa`, `ID_cupom`) VALUES
	(1, NULL, NULL, NULL),
	(2, NULL, NULL, NULL),
	(3, NULL, NULL, NULL),
	(4, 15, 1, NULL),
	(5, 4, 1, NULL);

-- Copiando dados para a tabela base.carrinho_produtos: ~0 rows (aproximadamente)

-- Copiando dados para a tabela base.categorias: ~5 rows (aproximadamente)
INSERT IGNORE INTO `categorias` (`ID`, `nome`, `img`, `ativo`) VALUES
	(1, 'Bolos', '../resources/uploads/categorias/66ce85344d0c2_bolo.jpg', 1),
	(2, 'Massas', '../resources/uploads/categorias/66ce85bb028c2_massas_frescas_fatto_mano_zona_sul.jpg', 1),
	(3, 'Donuts', '../resources/uploads/categorias/66ce86ab3fc83_donuts.jpeg', 1),
	(4, 'Brownies', '../resources/uploads/categorias/66ce86f06fed3_340593-original.jpg', 1),
	(5, 'Docinhos', '../resources/uploads/categorias/66ce87114eb99_docinho-brigadeiro-cpt.jpg', 1);

-- Copiando dados para a tabela base.categorias_empresas: ~5 rows (aproximadamente)
INSERT IGNORE INTO `categorias_empresas` (`ID`, `ID_empresa`, `ID_categoria`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5);

-- Copiando dados para a tabela base.cupons: ~0 rows (aproximadamente)
INSERT IGNORE INTO `cupons` (`ID`, `nome`, `dt_fim`, `ID_empresa`, `quant_usos`, `valor_desc`, `ativo`) VALUES
	(1, 'Teste', NULL, 1, 2, 20, 1);

-- Copiando dados para a tabela base.empresas: ~2 rows (aproximadamente)
INSERT IGNORE INTO `empresas` (`ID`, `nome`, `cnpj`, `ativo`, `whatsapp`, `instagram`, `telefone`, `facebook`, `valor_minimo`) VALUES
	(1, 'Frontex', '11111111111111', 1, '27999598289', '', '', '', 10.00),
	(2, 'Backex', '22222222222222', 1, NULL, NULL, NULL, NULL, NULL);

-- Copiando dados para a tabela base.empresa_produtos: ~5 rows (aproximadamente)
INSERT IGNORE INTO `empresa_produtos` (`ID`, `ID_empresa`, `ID_produto`) VALUES
	(1, 1, 22),
	(2, 1, 31),
	(3, 1, 32),
	(4, 1, 33),
	(5, 1, 34);

-- Copiando dados para a tabela base.formaspagamento: ~3 rows (aproximadamente)
INSERT IGNORE INTO `formaspagamento` (`ID`, `nome`) VALUES
	(1, 'Cartão de Crédito'),
	(2, 'PIX'),
	(3, 'Cartão de Débito');

-- Copiando dados para a tabela base.formaspagamento_empresas: ~3 rows (aproximadamente)
INSERT IGNORE INTO `formaspagamento_empresas` (`ID`, `ID_formapagamento`, `ID_empresa`) VALUES
	(2, 1, 1),
	(3, 2, 1),
	(4, 3, 1);

-- Copiando dados para a tabela base.pedidos: ~8 rows (aproximadamente)
INSERT IGNORE INTO `pedidos` (`ID`, `nome`, `ID_empresa`, `endereco`, `bairro`, `numero`, `cidade`, `estado`, `cep`, `complemento`, `data_pedido`, `ID_formapagamento`, `status`, `ID_usuario`, `ID_cupom`) VALUES
	(17, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-19 15:19:41', 1, 1, 15, NULL),
	(18, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-18 15:19:41', 1, 2, 15, NULL),
	(19, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-17 15:19:41', 1, 3, 15, NULL),
	(20, 'Teste', 1, 'Teste', 'Teste', '2354', 'Teste', 'AC', '2245255', 'Teste', '2024-10-19 15:19:41', 1, 4, 4, NULL),
	(21, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-19 15:19:41', 3, 3, 15, NULL),
	(22, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-19 16:10:29', 1, 4, 15, NULL),
	(23, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-26 09:26:58', 1, 4, 15, NULL),
	(24, 'Nível 2', 1, 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', '29709185', 'Teste', '2024-10-26 10:14:52', 3, 3, 15, NULL);

-- Copiando dados para a tabela base.pedidos_produtos: ~15 rows (aproximadamente)
INSERT IGNORE INTO `pedidos_produtos` (`ID`, `ID_produto`, `ID_pedido`, `qtd`) VALUES
	(23, 22, 17, 1),
	(24, 31, 17, 1),
	(25, 22, 18, 1),
	(26, 31, 18, 1),
	(27, 32, 19, 2),
	(28, 31, 19, 1),
	(29, 22, 20, 1),
	(30, 31, 20, 1),
	(31, 31, 21, 1),
	(32, 22, 21, 1),
	(33, 32, 21, 1),
	(34, 22, 22, 1),
	(35, 22, 23, 4),
	(36, 31, 23, 1),
	(37, 31, 24, 1);

-- Copiando dados para a tabela base.produtos: ~5 rows (aproximadamente)
INSERT IGNORE INTO `produtos` (`ID`, `nome`, `img`, `descricao`, `preco_venda`, `preco_promocao`, `ID_categoria`, `ativo`) VALUES
	(22, 'Bolo', '../resources/uploads/produtos/66e1b16cc1be0_bolo.jpg', 'Teste', 22.34, 20.00, 1, 1),
	(31, 'Donuts sortidos', '../resources/uploads/produtos/66ce8957f3bba_4c16f98edb2d1c4792f2b7855cecc3c1.jpg', 'Teste', 22.23, 0.00, 3, 1),
	(32, 'Bolo2', '../resources/uploads/produtos/66ce9797d2d59_depositphotos_252391082-stock-photo-sweet-chocolate-cake-on-wooden.jpg', 'Teste', 40.50, 38.30, 4, 1),
	(33, 'Teste', '../resources/uploads/produtos/66cea305cd438_depositphotos_252391082-stock-photo-sweet-chocolate-cake-on-wooden.jpg', 'Teste', 22.22, 0.00, 2, 1),
	(34, 'Teste2', '../resources/uploads/produtos/66cea3123305e_4c16f98edb2d1c4792f2b7855cecc3c1.jpg', 'Teste', 222.22, 0.00, 5, 1);

-- Copiando dados para a tabela base.usuario: 0 rows
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

-- Copiando dados para a tabela base.usuarios: ~4 rows (aproximadamente)
INSERT IGNORE INTO `usuarios` (`ID`, `nome`, `email`, `senha`, `nivel`, `ID_empresa`, `telefone`, `endereco`, `bairro`, `numero`, `cidade`, `estado`, `complemento`, `cpf`, `cep`, `img`) VALUES
	(0, 'admin', 'admin@admin.com', '$2y$10$M3/TOgkgmObJu/..osL8mOExt7xqJvHIxzelOnTz8JBLnPHP1Fnk.', 4, 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'Teste', 'lorenzocoslop2709@gmail.com', '$2y$10$0Gz.So7wUN/OfwN4Dkj4rONvPdv7hllPTeQlYAlPEmyhzdVTPmmBG', 1, 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, 'Nível 1', 'nivel1@email.com', '$2y$10$KE8HBEYgeYjuloCwupxwTeqHytljiQqZiQ.v0.FNq/jqxyQL5T2TC', 2, 1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(15, 'Nível 2', 'nivel2@email.com', '$2y$10$DO/cEDzY0pZeMIKUydN4DudT6JZSL4MY05bdpaME6dvm86mqUTp.u', 3, 1, '', 'Rua Oswaldo Quedevez', 'Santa Mônica', '140', 'Colatina', 'ES', 'Teste', NULL, '29709185', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
