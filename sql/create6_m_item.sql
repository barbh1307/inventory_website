
-- --------------------------------------------------------

--
-- Table structure for table `m_item`
--

CREATE TABLE `m_item` (
  `id_master_item` int(11) NOT NULL,
  `k_itemnumber` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `itemdescription` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `dimension_x` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `dimension_y` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `dimension_z` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `rabbet` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `lip` decimal(8,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

