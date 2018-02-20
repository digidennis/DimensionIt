<?php


class Digidennis_DimensionIt_Helper_Slot extends Mage_Core_Helper_Abstract
{
    public function optionHasSlot($option)
    {
        if(is_object($option))
            $option = $option->getOptionId();

        return Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->filterForOption($option)
            ->getSize() > 0;
    }

    public function getOptionSlots($option)
    {
        if(is_object($option))
            $option = $option->getOptionId();

        return Mage::getModel('digidennis_dimensionit/slot')
                ->getCollection()
                ->filterForOption($option);
    }

    public function getOptionTypeSlot($option_type_id)
    {
        return Mage::getModel('digidennis_dimensionit/slot')->load($option_type_id, 'option_type_id');
    }

}