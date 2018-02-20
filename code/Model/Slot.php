<?php

class Digidennis_DimensionIt_Model_Slot extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_dimensionit/slot');
    }

    public function getDimensions()
    {
        if( $this->hasData('dimensions'))
            return $this->getData('dimensions');

        $dimensions =  Mage::getModel('digidennis_dimensionit/dimension')
            ->getCollection()
            ->addFieldToFilter('slot_id', $this->getSlotId());
        $this->setData('dimensions', $dimensions);
        return $dimensions;
    }
}