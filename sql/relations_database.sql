
--
-- Indexes for dumped tables
--

--
-- Indexes for table `ii_costofgood`
--
ALTER TABLE `ii_costofgood`
  ADD PRIMARY KEY (`id_instanceitem_costofgood`),
  ADD KEY `fk_iicost_invoicenumber_minv` (`fk_iicost_invoicenumber_minv`),
  ADD KEY `fk_iicost_itemnumber_mitem` (`fk_iicost_itemnumber_mitem`),
  ADD KEY `fk_iicost_unit_mitemunit` (`fk_iicost_unit_mitemunit`);

--
-- Indexes for table `ii_frameready`
--
ALTER TABLE `ii_frameready`
  ADD PRIMARY KEY (`id_instanceitem_frameready`),
  ADD KEY `fk_iifram_itemnumber_mitem` (`fk_iifram_itemnumber_mitem`),
  ADD KEY `fk_iifram_unit_mitemunit` (`fk_iifram_unit_mitemunit`);

--
-- Indexes for table `ii_physical`
--
ALTER TABLE `ii_physical`
  ADD PRIMARY KEY (`id_instanceitem_physical`),
  ADD KEY `fk_iiphys_itemnumber_mitem` (`fk_iiphys_itemnumber_mitem`),
  ADD KEY `fk_iiphys_unit_mitemunit` (`fk_iiphys_unit_mitemunit`),
  ADD KEY `fk_iiphys_enteredby_muser` (`fk_iiphys_enteredby_muser`);

--
-- Indexes for table `ii_working`
--
ALTER TABLE `ii_working`
  ADD PRIMARY KEY (`id_instanceitem_working`),
  ADD KEY `k_itemnumber_idx` (`fk_iiwork_itemnumber_mitem`),
  ADD KEY `id_instanceitem_physical_idx` (`fk_iiwork_id_iiphys`),
  ADD KEY `k_username_idx` (`fk_iiwork_enteredby_muser`);

--
-- Indexes for table `m_company`
--
ALTER TABLE `m_company`
  ADD PRIMARY KEY (`id_master_company`),
  ADD UNIQUE KEY `k_companycode_idx` (`k_companycode`);

--
-- Indexes for table `m_invoice`
--
ALTER TABLE `m_invoice`
  ADD PRIMARY KEY (`id_master_invoice`),
  ADD UNIQUE KEY `k_invoicenumber_idx` (`k_invoicenumber`),
  ADD KEY `fk_minvo_companycode_mcomp` (`fk_minvo_companycode_mcomp`),
  ADD KEY `fk_minvo_enteredby_muser` (`fk_minvo_enteredby_muser`);

--
-- Indexes for table `m_item`
--
ALTER TABLE `m_item`
  ADD PRIMARY KEY (`id_master_item`),
  ADD UNIQUE KEY `k_itemnumber_idx` (`k_itemnumber`);

--
-- Indexes for table `m_itemunit`
--
ALTER TABLE `m_itemunit`
  ADD PRIMARY KEY (`id_master_itemunit`),
  ADD UNIQUE KEY `k_unit_idx` (`k_unit`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`id_master_user`),
  ADD UNIQUE KEY `k_username_idx` (`k_username`),
  ADD KEY `fk_muser_rolecode_muserrole` (`fk_muser_rolecode_muserrole`);

--
-- Indexes for table `m_userrole`
--
ALTER TABLE `m_userrole`
  ADD PRIMARY KEY (`id_master_userrole`),
  ADD UNIQUE KEY `k_rolecode_idx` (`k_rolecode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ii_costofgood`
--
ALTER TABLE `ii_costofgood`
  MODIFY `id_instanceitem_costofgood` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;
--
-- AUTO_INCREMENT for table `ii_frameready`
--
ALTER TABLE `ii_frameready`
  MODIFY `id_instanceitem_frameready` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1333;
--
-- AUTO_INCREMENT for table `ii_physical`
--
ALTER TABLE `ii_physical`
  MODIFY `id_instanceitem_physical` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1802;
--
-- AUTO_INCREMENT for table `ii_working`
--
ALTER TABLE `ii_working`
  MODIFY `id_instanceitem_working` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `m_company`
--
ALTER TABLE `m_company`
  MODIFY `id_master_company` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_invoice`
--
ALTER TABLE `m_invoice`
  MODIFY `id_master_invoice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;
--
-- AUTO_INCREMENT for table `m_item`
--
ALTER TABLE `m_item`
  MODIFY `id_master_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4680;
--
-- AUTO_INCREMENT for table `m_itemunit`
--
ALTER TABLE `m_itemunit`
  MODIFY `id_master_itemunit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `id_master_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `m_userrole`
--
ALTER TABLE `m_userrole`
  MODIFY `id_master_userrole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ii_costofgood`
--
ALTER TABLE `ii_costofgood`
  ADD CONSTRAINT `fk_iicost_invoicenumber_minv` FOREIGN KEY (`fk_iicost_invoicenumber_minv`) REFERENCES `m_invoice` (`k_invoicenumber`),
  ADD CONSTRAINT `fk_iicost_itemnumber_mitem` FOREIGN KEY (`fk_iicost_itemnumber_mitem`) REFERENCES `m_item` (`k_itemnumber`),
  ADD CONSTRAINT `fk_iicost_unit_mitemunit` FOREIGN KEY (`fk_iicost_unit_mitemunit`) REFERENCES `m_itemunit` (`k_unit`);

--
-- Constraints for table `ii_frameready`
--
ALTER TABLE `ii_frameready`
  ADD CONSTRAINT `fk_iifram_itemnumber_mitem` FOREIGN KEY (`fk_iifram_itemnumber_mitem`) REFERENCES `m_item` (`k_itemnumber`),
  ADD CONSTRAINT `fk_iifram_unit_mitemunit` FOREIGN KEY (`fk_iifram_unit_mitemunit`) REFERENCES `m_itemunit` (`k_unit`);

--
-- Constraints for table `ii_physical`
--
ALTER TABLE `ii_physical`
  ADD CONSTRAINT `fk_iiphys_enteredby_muser` FOREIGN KEY (`fk_iiphys_enteredby_muser`) REFERENCES `m_user` (`k_username`),
  ADD CONSTRAINT `fk_iiphys_itemnumber_mitem` FOREIGN KEY (`fk_iiphys_itemnumber_mitem`) REFERENCES `m_item` (`k_itemnumber`),
  ADD CONSTRAINT `fk_iiphys_unit_mitemunit` FOREIGN KEY (`fk_iiphys_unit_mitemunit`) REFERENCES `m_itemunit` (`k_unit`);

--
-- Constraints for table `ii_working`
--
ALTER TABLE `ii_working`
  ADD CONSTRAINT `id_instanceitem_physical` FOREIGN KEY (`fk_iiwork_id_iiphys`) REFERENCES `ii_physical` (`id_instanceitem_physical`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `k_itemnumber` FOREIGN KEY (`fk_iiwork_itemnumber_mitem`) REFERENCES `m_item` (`k_itemnumber`),
  ADD CONSTRAINT `k_username` FOREIGN KEY (`fk_iiwork_enteredby_muser`) REFERENCES `m_user` (`k_username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `m_invoice`
--
ALTER TABLE `m_invoice`
  ADD CONSTRAINT `fk_minvo_companycode_mcomp` FOREIGN KEY (`fk_minvo_companycode_mcomp`) REFERENCES `m_company` (`k_companycode`),
  ADD CONSTRAINT `fk_minvo_enteredby_muser` FOREIGN KEY (`fk_minvo_enteredby_muser`) REFERENCES `m_user` (`k_username`);

--
-- Constraints for table `m_user`
--
ALTER TABLE `m_user`
  ADD CONSTRAINT `fk_muser_rolecode_muserrole` FOREIGN KEY (`fk_muser_rolecode_muserrole`) REFERENCES `m_userrole` (`k_rolecode`);
