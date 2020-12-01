
-- --------------------------------------------------------

--
-- Table structure for table `ii_working`
--

CREATE TABLE `ii_working` (
  `id_instanceitem_working` int(11) NOT NULL,
  `fk_iiwork_itemnumber_mitem` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `job_number` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `dateentered` datetime NOT NULL,
  `fk_iiwork_enteredby_muser` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `fk_iiwork_id_iiphys` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

