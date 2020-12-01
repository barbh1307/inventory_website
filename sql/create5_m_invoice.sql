
-- --------------------------------------------------------

--
-- Table structure for table `m_invoice`
--

CREATE TABLE `m_invoice` (
  `id_master_invoice` int(11) NOT NULL,
  `k_invoicenumber` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `invoicedate` date NOT NULL,
  `fk_minvo_companycode_mcomp` varchar(4) COLLATE utf8mb4_bin NOT NULL,
  `fk_minvo_enteredby_muser` varchar(20) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

