
-- --------------------------------------------------------

--
-- Table structure for table `ii_physical`
--

CREATE TABLE `ii_physical` (
  `id_instanceitem_physical` int(11) NOT NULL,
  `fk_iiphys_itemnumber_mitem` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `fk_iiphys_unit_mitemunit` varchar(8) COLLATE utf8mb4_bin NOT NULL,
  `fk_iiphys_enteredby_muser` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `dimensionlength` decimal(8,4) NOT NULL,
  `dimensionwidth` decimal(8,4) NOT NULL,
  `location` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  `dateentered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

