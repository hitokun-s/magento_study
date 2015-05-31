<?php 
$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('cataloginventory/stock_item')} ADD `reserved_qty` decimal(12,4) NOT NULL DEFAULT '0.0000';");
$installer->endSetup();