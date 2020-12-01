
-- --------------------------------------------------------

--
-- Table structure for table `ii_frameready`
--

CREATE TABLE `ii_frameready` (
  `id_instanceitem_frameready` int(11) NOT NULL,
  `fk_iifram_itemnumber_mitem` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `fk_iifram_unit_mitemunit` varchar(8) COLLATE utf8mb4_bin NOT NULL,
  `quantity` decimal(8,4) NOT NULL,
  `inventorydate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

