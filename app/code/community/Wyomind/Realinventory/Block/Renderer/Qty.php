<?php

class Wyomind_Realinventory_Block_Renderer_Qty extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {

        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($row->getId());
		$qty= round($stock->getReservedQty());
		if(!$qty) return "-";
		if (!$stock->getIsQtyDecimal())
		 return "<b style='color:red;'>".$qty.'</b>';
		      
		 return "<b style='color:red;'>". $stock->getReservedQty().'</b>';
		 
	    
    }

}

