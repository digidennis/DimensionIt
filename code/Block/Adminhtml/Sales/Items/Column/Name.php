<?php

class Digidennis_DimensionIt_Block_Adminhtml_Sales_Items_Column_Name extends Mage_Adminhtml_Block_Sales_Items_Column_Name
{
    public function getPostedDimensionLabels()
    {
        /*$help = Mage::helper('digidennis_dimensionit/dimension');
        $item = $this->getItem();
        $dimensions = $item->getBuyRequest()->getDimensions();
        return $help->getOutputedLabels($dimensions);*/

    }

    public function getOptionDimensionLabel($option)
    {
        /*$item = $this->getItem();
        $dimensions = $item->getBuyRequest()->getDimensions();
        $help = Mage::helper('digidennis_dimensionit/dimension');*/
    }
}
