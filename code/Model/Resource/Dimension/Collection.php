<?php


class Digidennis_DimensionIt_Model_Resource_Dimension_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define resource model and model
     *
     */
    protected function _construct()
    {
        $this->_init('digidennis_dimensionit/dimension');
    }

    public function filterForProduct($product_id)
    {
        $this->addFieldToFilter('product_id', $product_id);
        $this->getSelect()->joinLeft(
            array('slot' => 'digidennis_dimensionit_slot'),
            "main_table.slot_id = slot.slot_id",
            array()
        );
        $this->getSelect()->joinLeft(
            array('type_title' => 'catalog_product_option_type_title'),
            "slot.option_type_id = type_title.option_type_id",
            array('typetitle' => 'type_title.title')
        );
        $this->getSelect()->joinLeft(
            array('type_value' => 'catalog_product_option_type_value'),
            "type_value.option_type_id = slot.option_type_id",
            array()
        );
        $this->getSelect()->joinLeft(
            array('option_title' => 'catalog_product_option_title'),
            "option_title.option_id = type_value.option_id",
            array('optiontitle'  => 'option_title.title')
        );
        return $this;
    }

    public function countInProduct($product_id)
    {
        $this->addFieldToFilter('product_id', $product_id);
        $this->getSelect()->joinLeft(
            array('slot' => 'digidennis_dimensionit_slot'),
            "main_table.slot_id = slot.slot_id",
            array()
        );
        return $this->getSize();
    }

}