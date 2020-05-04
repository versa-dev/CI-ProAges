CREATE TABLE IF NOT EXISTS `extra_payment` (
  `x_product_platform` int(11) NOT NULL,
  `x_currency` int(11) NOT NULL,
  `x_payment_interval` int(11) NOT NULL,
  `extra_percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `extra_payment` (`x_product_platform`, `x_currency`, `x_payment_interval`, `extra_percentage`) VALUES
(1, 1, 1, 0.085), -- platform 1, MN, Mensual
(1, 1, 2, 0.065), -- platform 1, MN, Trimestrial
(1, 1, 3, 0.045), -- platform 1, MN, Semestrial
(1, 1, 4, 0), -- platform 1, MN, Anual
(1, 2, 1, 0.04), -- platform 1, USD, Mensual
(1, 2, 2, 0.0325), -- platform 1, USD, Trimestrial
(1, 2, 3, 0.0225), -- platform 1, USD, Semestrial
(1, 2, 4, 0), -- platform 1, USD, Anual
(2, 1, 1, 0.0438), -- platform 2, MN, Mensual
(2, 1, 2, 0.0408), -- platform 2, MN, Trimestrial
(2, 1, 3, 0.0363), -- platform 2, MN, Semestrial
(2, 1, 4, 0), -- platform 2, MN, Anual
(2, 2, 1, 0.04), -- platform 2, USD, Mensual
(2, 2, 2, 0.04), -- platform 2, USD, Trimestrial
(2, 2, 3, 0.04), -- platform 2, USD, Semestrial
(2, 2, 4, 0); -- platform 2, USD, Anual

