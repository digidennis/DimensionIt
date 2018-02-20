<?php


class Digidennis_DimensionIt_Model_Observer
{
    public function productTypePrepareFullOptions(Varien_Event_Observer $observer)
    {
        $posteddimensions = null;
        $product = $observer->getProduct();
        $buyRequest = $observer->getBuyRequest();
        if( $buyRequest )
        {
            $posteddimensions = $buyRequest->getData('dimensions');
            if( $posteddimensions )
            {
                $product->addCustomOption('posteddimensions', serialize($posteddimensions));
            }
        }

        //keep whatever additional options there is..
        $additionalOptions = array();
        if ($additionalOption = $product->getCustomOption('additional_options'))
        {
            $additionalOptions = (array) unserialize($additionalOption->getValue());
        }

        $labels = Mage::helper('digidennis_dimensionit/dimension')->getOutputedLabels($posteddimensions);

        if( count($labels) )
        {
            $buffer = '';
            foreach ($labels as $label => $value )
            {
                $buffer .= $label . ' ' . $value[0] . $value[1] . ', ';
            }
            $buffer = Mage::helper('core/string')->substr($buffer, 0, -2);
            $additionalOptions[] = array_merge(
                array('code' => 'dimension_total', 'label' => 'Mål', 'value' => $buffer )
            );
            $product->addCustomOption('additional_options', serialize($additionalOptions));
        }
    }

    public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getItem();
        if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
            $orderItem = $observer->getOrderItem();
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }
    }
}