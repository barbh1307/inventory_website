-- --------------------------------------------------------

--
-- Table structure for table `m_userrole`
--

CREATE TABLE `m_userrole` (
  `id_master_userrole` int(11) NOT NULL,
  `k_rolecode` int(11) NOT NULL,
  `roledescription` varchar(20) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
