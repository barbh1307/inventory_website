
-- --------------------------------------------------------

--
-- Table structure for table `m_company`
--

CREATE TABLE `m_company` (
  `id_master_company` int(11) NOT NULL,
  `k_companycode` varchar(4) COLLATE utf8mb4_bin NOT NULL,
  `companyname` varchar(45) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

