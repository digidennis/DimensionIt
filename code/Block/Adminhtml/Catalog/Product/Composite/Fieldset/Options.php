<?php

/**
 * Adminhtml block for fieldset of product custom options
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Composite_Fieldset_Options extends Mage_Adminhtml_Block_Catalog_Product_Composite_Fieldset_Options
{
    public function getOptionSlots( $option )
    {
        return Mage::helper('digidennis_dimensionit/slot')->getOptionSlots($option);
    }
}
