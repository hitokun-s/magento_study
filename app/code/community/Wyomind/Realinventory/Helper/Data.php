<?php

class Wyomind_Realinventory_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getOrderedItems($order) {
        $orderedItems = array();
        $items = $order->getAllItems();
        $i = 0;
        foreach ($items as $itemId => $item) {
            if ($item->getQtyOrdered() > 0) {
                $orderedItems[$i]["sku"] = $item->getSku();
                $orderedItems[$i]["qty"] = $item->getQtyOrdered();
                $orderedItems[$i]["id"] = Mage::getModel("catalog/product")->getIdBySku($orderedItems[$i]["sku"]);
                $orderedItems[$i]["name"] = $item->getName();
                $i++;
            }
        }

        return $orderedItems;
    }

    public function incrementReservedStocks($orderedItems) {



        $temp = array();

        foreach ($orderedItems as $orderedItem) {

            if (!in_array($orderedItem["id"], $temp)) {
                $temp[] = $orderedItem["id"];
                $stock = Mage::getModel("cataloginventory/stock_item")->loadByProduct($orderedItem["id"]);

                if ($stock->getManageStock() || (Mage::getStoreConfig("cataloginventory/item_options/manage_stock") && $stock->getUseConfigManageStock())) {
                    $data["reserved_qty"] = $stock->getReservedQty() + $orderedItem["qty"];
                    $stock->setReservedQty($data["reserved_qty"])->save();
//Mage::getSingleton("core/session")->addSuccess("LOG:: +++ " . $orderedItem["id"] . " => " . $orderedItem["qty"] . "<br>");
                }
            }
        }
    }

    public function decrementReservedStocks($orderedItems) {
        $temp = array();
        foreach ($orderedItems as $orderedItem) {

            if (!in_array($orderedItem["id"], $temp)) {
                $temp[] = $orderedItem["id"];
                $stock = Mage::getModel("cataloginventory/stock_item")->loadByProduct($orderedItem["id"]);
                if ($stock->getManageStock() || (Mage::getStoreConfig("cataloginventory/item_options/manage_stock") && $stock->getUseConfigManageStock())) {
                    $data["reserved_qty"] = $stock->getReservedQty() - $orderedItem["qty"];
                    $stock->setReservedQty($data["reserved_qty"])->save();
//Mage::getSingleton("core/session")->addSuccess("LOG:: --- " . $orderedItem["id"] . " => " . $orderedItem["qty"] . "<br>");
                }
            }
        }
    }

}