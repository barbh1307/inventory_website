
-- --------------------------------------------------------

--
-- Structure for view `v_specialmat`
--
DROP TABLE IF EXISTS `v_specialmat`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_specialmat`  AS  select `ii_physical`.`fk_iiphys_itemnumber_mitem` AS `vitemnumber`,`ii_physical`.`dimensionlength` AS `vphysL`,`ii_physical`.`dimensionwidth` AS `vphysW`,`m_item`.`dimension_x` AS `vmasL`,`m_item`.`dimension_y` AS `vmasW` from (`ii_physical` join `m_item` on((`ii_physical`.`fk_iiphys_itemnumber_mitem` = `m_item`.`k_itemnumber`))) where (`ii_physical`.`dateentered` between '2017-01-01' and '2017-12-31') ;
