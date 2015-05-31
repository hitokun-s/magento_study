<?php

class Wyomind_Realinventory_Model_Observer {

    public function addActionColumn(Varien_Event_Observer $observer) {

        $block = $observer->getEvent()->getBlock();
        $this->_block = $block;

        if (get_class($block) == Mage::getStoreConfig("realinventory/system/grid")) {

            $actions = array();


            $block->addColumnAfter("reserved_qty", array(
                "header" => Mage::helper("realinventory")->__("Reserved qty"),
                "width" => "50px",
                "type" => "text",
                "align" => "center",
                "filter" => false,
                "sortable" => true,
                "renderer" => "Wyomind_Realinventory_Block_Renderer_Qty",
                    ), "qty");
        }


        return $observer;
    }

    public function reserve($observer) {
        $order = $observer->getEvent()->getOrder();
       
        if ($order->getCreatedAt() > Mage::getStoreConfig("realinventory/system/from_date")) {
            
            $orderedItems = Mage::helper("realinventory/data")->getOrderedItems($order);
            Mage::helper("realinventory/data")->incrementReservedStocks($orderedItems);
            //Mage::getSingleton("core/session")->addSuccess("LOG::reserve");
        }
    }

    public function cancel($observer) {
        $order = $observer->getEvent()->getPayment()->getOrder();
        if ($order->getCreatedAt() > Mage::getStoreConfig("realinventory/system/from_date")) {
            $orderedItems = Mage::helper("realinventory/data")->getOrderedItems($order);
            Mage::helper("realinventory/data")->decrementReservedStocks($orderedItems);
        }

        //Mage::getSingleton("core/session")->addSuccess("LOG::cancel");
    }

    public function refund($observer) {
        $creditmemo = $observer->getEvent()->getCreditmemo();

        $items = array();

        foreach ($creditmemo->getAllItems() as $item) {
            if (!in_array(Mage::getModel("catalog/product")->load($item->getProductId())->getTypeId(), array("configurable", "bundle", "grouped"))) {
                $return = false;
                if ($item->hasBackToStock()) {
                    if ($item->getBackToStock() && $item->getQty()) { //qtyRefunded ?
                        $return = true;
                    }
                } elseif (Mage::helper("cataloginventory")->isAutoReturnEnabled()) {
                    $return = true;
                }
                if ($return) {
                    Mage::helper("realinventory/data")->decrementReservedStocks(array(array("qty" => $item->getQty(), "id" => $item->getProductId())));
                }
            }
        }
    }

    public function shipped($observer) {

        $shipment = $observer->getEvent()->getShipment();
        $items = $shipment->getItemsCollection();
        foreach ($items as $item) {
            if (!in_array(Mage::getModel("catalog/product")->load($item->getProductId())->getTypeId(), array("configurable", "bundle", "grouped"))) {
                Mage::helper("realinventory/data")->decrementReservedStocks(array(array("qty" => $item->getQty(), "id" => $item->getProductId())));
            }
        }
        //Mage::getSingleton("core/session")->addSuccess("LOG::shipped");
    }

}
