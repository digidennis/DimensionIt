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

    public function productSaveAfter(Varien_Event_Observer $observer)
    {
        $editslots = $_POST['digidennis-dimensionit-slot-edit'];
        $product = $observer->getProduct();
        $savedslots = Mage::getModel('digidennis_dimensionit/slot')
            ->getCollection()
            ->addFieldToFilter('product_id', $product->getEntityId());

        //no editslots but savedslots, delete all in collection
        if( !count($editslots) && $savedslots->getSize() ){
            foreach ($savedslots as $savedslot){
                $savedslot->delete();
            }
            return;
        }

        //handle deleted slots
        foreach ($savedslots as $savedslot ) {
            $inedit = false;
            foreach ($editslots as $slotid => $editslot) {
                if( $savedslot->getSlotId() == $slotid ){
                    $inedit = true;
                    break;
                }
            }
            if(!$inedit)
                $savedslot->delete();
        }

        // handle existing and new slot
        foreach ($editslots as $slotid => $editslot) {
            $iterationslot = null;
            //handle newslots
            if(strpos($slotid,'newslot-') === 0){
                $iterationslot = Mage::getModel('digidennis_dimensionit/slot');
            } else{
                $iterationslot = Mage::getModel('digidennis_dimensionit/slot')->load($slotid);
                //has id - valid, else null
                if( !$iterationslot->getSlotId() )
                    $iterationslot = null;
            }
            if($iterationslot) {
                $iterationslot->setMin($editslot['min'] == '' ? null : $editslot['min'] );
                $iterationslot->setMax($editslot['max'] == '' ? null : $editslot['max']);
                $iterationslot->setPrice($editslot['price']);
                $iterationslot->setWeight($editslot['weight'] == '' ? null : $editslot['weight']);
                $iterationslot->setVolume($editslot['volume'] == '' ? null : $editslot['volume']);
                $iterationslot->setVolumeunit($editslot['volumeunit'] == '' ? null : $editslot['volumeunit']);
                $iterationslot->setCost($editslot['cost'] == '' ? null : $editslot['cost']);
                $iterationslot->setOptionTypeId($editslot['optionTypeId'] == '' ? null : $editslot['optionTypeId']);
                $iterationslot->setProductId($product->getEntityId());
                $iterationslot->save();
                $editslot['id'] = $iterationslot->getSlotId(); //update id. new slots has faux id
                $this->updateDimensionsInSlot($editslot, $iterationslot);
            }
        }
    }

    private function updateDimensionsInSlot( $editslot, $savedslot )
    {
        $saveddimensions = $savedslot->getDimensions();
        $editdimensions = $editslot['dimensions'];

        //no editdimensions but saveddimensions, delete all in collection
        if( !count($editdimensions) && $saveddimensions->getSize() ){
            foreach ($saveddimensions as $saveddimension){
                $saveddimension->delete();
            }
            return;
        }

        //handle deleted dimensions
        foreach ($saveddimensions as $saveddimension ) {
            $inedit = false;
            foreach ($editdimensions as $dimensionid => $editdimension) {
                if( $saveddimension->getDimensionId() == $dimensionid ){
                    $inedit = true;
                    break;
                }
            }
            if(!$inedit)
                $saveddimension->delete();
        }

        // handle existing and new dimension
        foreach ($editdimensions as $dimensionid => $editdimension) {
            $iterationdimension = null;
            //handle newslots
            if(strpos($dimensionid,'newdimension-') === 0){
                $iterationdimension = Mage::getModel('digidennis_dimensionit/dimension');
            } else{
                $iterationdimension = Mage::getModel('digidennis_dimensionit/dimension')->load($dimensionid);
                //has id - valid, else null
                if( !$iterationdimension->getDimensionId() )
                    $iterationdimension = null;
            }
            if($iterationdimension) {
                $iterationdimension->setMin($editdimension['min'] == '' ? null : $editdimension['min'] );
                $iterationdimension->setMax($editdimension['max'] == '' ? null : $editdimension['max'] );
                $iterationdimension->setOutput( key_exists('output', $editdimension) ? 1 : null);
                $iterationdimension->setStep($editdimension['step'] == '' ? null : $editdimension['step']);
                $iterationdimension->setInitial($editdimension['initial'] == '' ? null : $editdimension['initial']);
                $iterationdimension->setUnit($editdimension['unit'] == '' ? null : $editdimension['unit']);
                $iterationdimension->setLabel($editdimension['label'] == '' ? null : $editdimension['label']);
                $iterationdimension->setSlotId($editslot['id']);
                $iterationdimension->save();
            }
        }

    }
}