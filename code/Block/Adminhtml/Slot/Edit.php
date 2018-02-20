<?php
class Digidennis_DimensionIt_Block_Adminhtml_Slot_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'digidennis_dimensionit_adminhtml';
        $this->_controller = 'dimensionit';

        /**
         * The $_mode property tells Magento which folder to use
         * to locate the related form blocks to be displayed in
         * this form container.
         */
        $this->_mode = 'edit';

        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('Edit')
            : $this->__('New');
        $this->_headerText =  $newOrEdit . ' ' . $this->__('Slot');
    }
}