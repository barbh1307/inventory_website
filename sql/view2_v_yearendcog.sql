
-- --------------------------------------------------------

--
-- Structure for view `v_yearendcog`
--
DROP TABLE IF EXISTS `v_yearendcog`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_yearendcog`  AS  select `m_invoice`.`invoicedate` AS `vinvoicedate`,`ii_costofgood`.`fk_iicost_itemnumber_mitem` AS `vitemnumber`,`ii_costofgood`.`fk_iicost_unit_mitemunit` AS `vunit`,`ii_costofgood`.`purchasequantity` AS `vquantity`,`ii_costofgood`.`priceperunit` AS `vprice` from (`m_invoice` join `ii_costofgood` on((`m_invoice`.`k_invoicenumber` = `ii_costofgood`.`fk_iicost_invoicenumber_minv`))) ;
