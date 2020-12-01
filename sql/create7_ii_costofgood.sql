
-- --------------------------------------------------------

--
-- Table structure for table `ii_costofgood`
--

CREATE TABLE `ii_costofgood` (
  `id_instanceitem_costofgood` int(11) NOT NULL,
  `fk_iicost_unit_mitemunit` varchar(8) COLLATE utf8mb4_bin NOT NULL,
  `fk_iicost_invoicenumber_minv` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `fk_iicost_itemnumber_mitem` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `purchasequantity` decimal(8,4) NOT NULL,
  `priceperunit` decimal(8,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

