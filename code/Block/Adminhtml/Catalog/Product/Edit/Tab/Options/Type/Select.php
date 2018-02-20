<?php


class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select extends
    Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Select
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('digidennis/dimensionit/catalog/product/edit/options/type/select.phtml');
    }
}