<?php


class Digidennis_DimensionIt_Helper_Dimension extends Mage_Core_Helper_Abstract
{
    public function getOutputedLabels( $dimensions )
    {
        $labels = array();
        foreach ( $dimensions as  $dimension )
        {
            if($dimension['output'] !== "1" )
                continue;

            if(key_exists($dimension['label'], $labels))
            {
                $labels[$dimension['label']][0] += floatval($dimension['value']);
            }
            else
            {
                $labels[$dimension['label']] = [floatval($dimension['value']),$dimension['unit']];
            }
        }
        return $labels;
    }

    public function getPostedDimensionsFromItem( $item )
    {
        $posteddimensions = false;
        if( $item->hasData('configuration_item_option') )
        {
            $option = $item->getData('configuration_item_option');
            if( $option->getProduct() )
                $customdimensionoption = $option->getProduct()->getCustomOption('posteddimensions');
            elseif ($option->getProductId())
                $customdimensionoption = Mage::getModel('catalog/product')->load($option->getProductId())->getCustomOption('posteddimensions');
            if($customdimensionoption)
                return unserialize($customdimensionoption->getValue());
        }
        elseif( $item->hasData('configuration_item'))
        {
            $codes = $item->getData('configuration_item')->getOptionsBycode();
            if( $codes && key_exists('posteddimensions', $codes ))
                return unserialize($codes['posteddimensions']->getValue());
        }
        return $posteddimensions;
    }

    public function getPostedDimensionsFromProduct( $product )
    {
        $posteddimensions = false;
        if( $product )
        {
            $customdimensionoption = $product->getCustomOption('posteddimensions');
            if($customdimensionoption)
                return unserialize($customdimensionoption->getValue());
        }
        return $posteddimensions;
    }

    public function getDimensionInProductCount( $product )
    {
        if(is_object($product))
            $product = $product->getId();

        return Mage::getResourceModel('digidennis_dimensionit/dimension_collection')->countInProduct($product);

    }

    public function getDimensionsForProductSlot($product)
    {
        if(is_object($product))
            $product = $product->getId();
        $slot = Mage::getResourceModel('digidennis_dimensionit/slot_collection')->filterForProduct($product);
        if($slot->count() > 0 )
            return $slot->getFirstItem()->getDimensions();

        return false;
    }

    public function getPostedOrInitialValue($dimension, $item_id)
    {
        $quote_item = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getItemById($item_id);
        if( $quote_item ) {
            $postedimensions = $quote_item->getOptionByCode('posteddimensions');
            if($postedimensions){
                $postedimensions = unserialize($postedimensions->getValue());
                if(key_exists($dimension->getDimensionId(), $postedimensions)){
                    return $postedimensions[$dimension->getDimensionId()]['value'];
                }
            }
        }
        return $dimension->getInitial();
    }
}