<?php

class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Edit_Tab extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/dimensionit/catalog/product/edit/tab.phtml');
    }

    protected function getSlotHtml($slot)
    {
        $block = $this->getLayout()
            ->createBlock('digidennis_dimensionit/adminhtml_catalog_product_edit_tab_slot', 'slot-' .$slot->getSlotId() );
        $block->setSlot($slot);
        return $block->toHtml();
    }

    protected function getGlobalSlot()
    {
        $productid = Mage::registry('current_product')->getId();
        $slot = Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->addFieldToFilter('product_id', $productid )
            ->addFieldToFilter('option_type_id', array('null' => true ))
            ->getFirstItem();

        if( $slot->getSlotId() )
            return $slot;
        return false;
    }

    protected function getLocalSlot($optiontypeid)
    {
        $productid = Mage::registry('current_product')->getId();
        $slot = Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->addFieldToFilter('product_id', $productid )
            ->addFieldToFilter('option_type_id', $optiontypeid )
            ->getFirstItem();

        if( $slot->getSlotId() )
            return $slot;
        return false;
    }

    protected function getSlotButtonHtml( $add = true )
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button');
        $button->setType('button');
        if($add){
            $button->setLabel( $this->__('Add Slot'));
            $button->setClass('add slot');
        } else {
            $button->setLabel( $this->__('Delete Slot'));
            $button->setClass('delete slot');
        }
        echo $button->toHtml();
    }

    public function getTabLabel() {
        return $this->__('DimensionIt');
    }

    public function getTabTitle() {
        return $this->__('DimensionIt');
    }

    public function canShowTab() {
        return true;
    }

    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    public function isHidden() {
        return false;
    }

}