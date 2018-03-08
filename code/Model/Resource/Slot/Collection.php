<?php

class Digidennis_DimensionIt_Model_Resource_Slot_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model and model
     *
     */
    protected function _construct()
    {
        $this->_init('digidennis_dimensionit/slot');
    }

    public function filterForOption($option_id)
    {
        $this->addFieldToFilter('option_id', $option_id);
        $this->getSelect()->joinRight(
            array('slot' => 'digidennis_dimensionit_slot'),
            'main_table.slot_id = slot.slot_id',
            array()
        );
        $this->getSelect()->joinRight(
            array('option_type' => 'catalog_product_option_type_value'),
            'main_table.option_type_id = option_type.option_type_id',
            array()
        );
        return $this;
    }

    public function filterForOptionType($option_type_id)
    {
        $this->addFieldToFilter('option_type_id', $option_type_id);
        return $this->getFirstItem();
    }

    public function filterForProduct($product_id)
    {
        $this->addFieldToFilter('product_id', $product_id);
        $this->addFieldToFilter('option_type_id', array('null' => true));
        return $this;
    }


    public function allInProduct($product_id)
    {
        $this->addFieldToFilter('product_id', $product_id);
        return $this;
    }

    public function countInProduct($product_id)
    {
        $this->addFieldToFilter('product_id', $product_id);
        return $this->getSize();
    }

}