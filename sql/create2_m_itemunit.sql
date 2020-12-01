-- --------------------------------------------------------

--
-- Table structure for table `m_itemunit`
--

CREATE TABLE `m_itemunit` (
  `id_master_itemunit` int(11) NOT NULL,
  `k_unit` varchar(8) COLLATE utf8mb4_bin NOT NULL,
  `unitdimension` varchar(25) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

