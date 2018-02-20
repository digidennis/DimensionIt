<?php

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Catalog'.DS.'ProductController.php');

class Digidennis_DimensionIt_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * Get custom products grid and serializer block
     */
    public function dimensionitAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

}