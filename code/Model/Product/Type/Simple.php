<?php

class Digidennis_DimensionIt_Model_Product_Type_Simple extends Mage_Catalog_Model_Product_Type_Simple
{
    /**
     * Default action to get weight of product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return decimal
     */
    public function getWeight($product = null)
    {
        $product = $this->getProduct($product);
        $posteddimensions = Mage::helper('digidennis_dimensionit/dimension')->getPostedDimensionsFromProduct($product);
        $solvedWeight = $product->getData('weight');
        $dimensionCalcHelper = Mage::helper('digidennis_dimensionit/calc');

        //global slot for product
        $globalslot = Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->filterForProduct($product->getId())
            ->getFirstItem();
        if( $globalslot && !is_null($globalslot->getWeight()))
        {
            $formular = $dimensionCalcHelper->processFormular($posteddimensions, $globalslot->getWeight());
            $solvedWeight += $dimensionCalcHelper->calculate($formular);
        }

        //For selected options do
        //get selected option ids
        if ($optionIds = $product->getCustomOption('option_ids'))
        {
            foreach (explode(',', $optionIds->getValue()) as $optionId)
            {
                //get the option
                if ($option = $product->getOptionById($optionId))
                {
                    //get this option selected values as array of ids
                    if($confItemOption = $product->getCustomOption('option_'.$option->getId()))
                    {
                        foreach(explode(',',$confItemOption->getValue()) as $selectedvalue)
                        {
                            $slot = Mage::getModel('digidennis_dimensionit/slot')->load($selectedvalue, 'option_type_id');
                            if( $slot && !is_null($slot->getWeight()))
                            {
                                $formular = $dimensionCalcHelper->processFormular($posteddimensions, $slot->getWeight());
                                $solvedWeight += $dimensionCalcHelper->calculate($formular);
                            }
                        }
                    }
                }
            }
        }

        return $solvedWeight;
    }
}