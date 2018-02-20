<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 27-08-2017
 * Time: 23:03
 */

class Digidennis_DimensionIt_Model_Product_Type_Price extends Mage_Catalog_Model_Product_Type_Price
{
    /**
     * Retrieve product final price
     *
     * @param float|null $qty
     * @param Mage_Catalog_Model_Product $product
     * @return float
     */
    public function getFinalPrice($qty = null, $product)
    {
        if (is_null($qty) && !is_null($product->getCalculatedFinalPrice())) {
            return $product->getCalculatedFinalPrice();
        }

        $finalPrice = $this->getBasePrice($product, $qty);
        $product->setFinalPrice($finalPrice);

        Mage::dispatchEvent('catalog_product_get_final_price', array('product' => $product, 'qty' => $qty));

        $finalPrice = $product->getData('final_price');

        $slot = Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->filterForProduct($product->getId())
            ->getFirstItem();

        if( $slot && !is_null($slot->getPrice()))
        {
            $posteddimensions = Mage::helper('digidennis_dimensionit/dimension')->getPostedDimensionsFromProduct($product);
            $processedForm = Mage::helper('digidennis_dimensionit/calc')->calculate(Mage::helper('digidennis_dimensionit/calc')->processFormular($posteddimensions, $slot->getPrice()));
            $newprice = floor(floatval($finalPrice)*$processedForm);

            //clamp to min or max
            if(!is_null($slot->getMin()) && $newprice < floatval($slot->getMin()))
                $newprice = floatval($slot->getMin());
            elseif(!is_null($slot->getMax()) && $newprice > floatval($slot->getMax()))
                $newprice = floatval($slot->getMax());

            $finalPrice = $newprice;
        }

        $finalPrice = $this->_applyOptionsPrice($product, $qty, $finalPrice);
        $finalPrice = max(0, $finalPrice);
        $product->setFinalPrice($finalPrice);

        return $finalPrice;
    }
}