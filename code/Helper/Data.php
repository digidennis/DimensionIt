<?php
class Digidennis_DimensionIt_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * calculate all volumes in order item, product and options
     * @return array|bool
     */
    public function getVolumesOfOrderItem(Mage_Sales_Model_Order_Item $item)
    {
        if(key_exists('dimensions', $item->getProductOptions()['info_buyRequest'])){
            $posteddimensions = $item->getProductOptions()['info_buyRequest']['dimensions'];
            $slotcollection = Mage::getResourceModel('digidennis_dimensionit/slot_collection')
                ->allInProduct($item->getProductId());
            $calc = Mage::helper('digidennis_dimensionit/calc');
            $return = array();
            foreach ( $slotcollection as $slot ) {
                $array[$slot->getSlotId()] = [
                    'option_type_id' =>  $slot->getOptionTypeId(),
                    'volume' => $calc->calculate($calc->processFormular($posteddimensions,$slot->getVolume())),
                    'unit' => $slot->getVolumeunit()
                ];
            }
            return $return;
        } else {
            return false;
        }
    }
    /**
     * calculate global volume in order item
     * @return array|bool
     */
    public function getGlobalVolumeOfOrderItem(Mage_Sales_Model_Order_Item $item)
    {
        if(key_exists('dimensions', $item->getProductOptions()['info_buyRequest'])){
            $posteddimensions = $item->getProductOptions()['info_buyRequest']['dimensions'];
            $globalslot = Mage::getResourceModel('digidennis_dimensionit/slot_collection')
                ->filterForProduct($item->getProductId())
                ->getFirstItem();
            if(!$globalslot->getSlotId())
                return false;
            $calc = Mage::helper('digidennis_dimensionit/calc');
            return [
                'option_type_id' =>  null,
                'volume' => $calc->calculate($calc->processFormular($posteddimensions,$globalslot->getVolume())),
                'unit' => $globalslot->getVolumeunit()
                ];
        } else {
            return false;
        }
    }
    /**
     * calculate option type volume in order item
     * @return array|bool
     */
    public function getOptionTypeVolumeOfOrderItem(Mage_Sales_Model_Order_Item $item, $option_type_id)
    {
        if(key_exists('dimensions', $item->getProductOptions()['info_buyRequest'])){
            $posteddimensions = $item->getProductOptions()['info_buyRequest']['dimensions'];
            $typeslot = Mage::getResourceModel('digidennis_dimensionit/slot_collection')
                ->filterForOptionType($option_type_id);
            if(!$typeslot->getSlotId())
                return false;
            $calc = Mage::helper('digidennis_dimensionit/calc');
            return [
                'option_type_id' =>  null,
                'volume' => $calc->calculate($calc->processFormular($posteddimensions,$typeslot->getVolume())),
                'unit' => $typeslot->getVolumeunit()
                ];
        } else {
            return false;
        }
    }
}