<?php

class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Edit_Tab_Slot extends Mage_Adminhtml_Block_Template
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/dimensionit/catalog/product/edit/tab/slot.phtml');
    }

    protected function getSlotDimensions()
    {
        if( $slot = $this->getSlot() ){
            return Mage::getModel('digidennis_dimensionit/dimension')
                ->getCollection()
                ->addFieldToFilter('slot_id', $slot->getSlotId());
        }
        return false;
    }

    protected function getAddButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button');
        $button->setType('button');
        $button->setLabel( $this->__('Add Dimension'));
        $button->setClass('dimension-add add');
        echo $button->toHtml();
    }

    protected function getDeleteButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button');
        $button->setType('button');
        $button->setClass('dimension-delete delete');
        echo $button->toHtml();
    }
}