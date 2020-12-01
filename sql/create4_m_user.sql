-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `id_master_user` int(11) NOT NULL,
  `k_username` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `userpassword` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `fk_muser_rolecode_muserrole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

