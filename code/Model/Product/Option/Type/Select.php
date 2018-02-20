<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 03-09-2017
 * Time: 22:47
 */
class Digidennis_DimensionIt_Model_Product_Option_Type_Select extends Mage_Catalog_Model_Product_Option_Type_Select
{
    /**
     * Return formatted option value ready to edit, ready to parse
     *
     * @param string $optionValue Prepared for cart option value
     * @return string
     */
    public function getEditableOptionValue($optionValue)
    {
        $option = $this->getOption();
        $result = '';
        $posteddimensions = Mage::helper('digidennis_dimensionit/dimension')->getPostedDimensionsFromItem($this);

        if (!$this->_isSingleSelection())
        {
            foreach (explode(',', $optionValue) as $_value)
            {
                if ($_result = $option->getValueById($_value))
                {
                    $slot = Mage::getModel('digidennis_dimensionit/slot')->load($_value,'option_type_id');
                    if( $slot && $slot->getSlotId() && $posteddimensions)
                    {
                        $dimbuffer = ' ';
                        foreach( $slot->getDimensions() as $dimension )
                            $dimbuffer .= $posteddimensions[$dimension->getDimensionId()]['label'] . ' ' . ($posteddimensions[$dimension->getDimensionId()]['value']+0) .  $posteddimensions[$dimension->getDimensionId()]['unit'] . ', ';
                        $dimbuffer = Mage::helper('core/string')->substr($dimbuffer, 0, -2);
                        $result .= $_result->getTitle() . $dimbuffer .  ', ';
                    }
                    else
                    {
                        $result .= $_result->getTitle() . ', ';
                    }
                }
                else
                {
                    if ($this->getListener())
                    {
                         $this->getListener()
                            ->setHasError(true)
                            ->setMessage(
                                $this->_getWrongConfigurationMessage()
                            );
                        $result = '';
                        break;
                    }
                }
            }
            $result = Mage::helper('core/string')->substr($result, 0, -2);
        }
        elseif ($this->_isSingleSelection())
        {
            if ($_result = $option->getValueById($optionValue))
            {
                $slot = Mage::getModel('digidennis_dimensionit/slot')
                    ->getCollection()
                    ->filterForOption($option->getOptionId())
                    ->getFirstItem();

                $dimbuffer = '';

                if( $slot && $slot->getSlotId() && $posteddimensions)
                {
                    foreach( $slot->getDimensions() as $dimension )
                        $dimbuffer .= $posteddimensions[$dimension->getDimensionId()]['label'] . ' ' . ($posteddimensions[$dimension->getDimensionId()]['value'] + 0) . $posteddimensions[$dimension->getDimensionId()]['unit'] . ', ';
                    $dimbuffer = Mage::helper('core/string')->substr($dimbuffer, 0, -2);
                }

                $result = $_result->getTitle() . ' ' . $dimbuffer;
            }
            else
            {
                if ($this->getListener())
                {
                    $this->getListener()
                        ->setHasError(true)
                        ->setMessage(
                            $this->_getWrongConfigurationMessage()
                        );
                }
                $result = '';
            }
        }
        else
        {
            $result = $optionValue;
        }
        return $result;
    }

    /**
     * Return Price for selected option
     *
     * @param string $optionValue Prepared for cart option value
     * @param float $basePrice
     * @return float
     */
    public function getOptionPrice($optionValue, $basePrice)
    {
        $option = $this->getOption();
        $result = 0;


        if (!$this->_isSingleSelection())
        {
            // not single selection so get selected ids by implode
            foreach(explode(',', $optionValue) as $value)
            {
                if ($_result = $option->getValueById($value))
                {
                    $slot = Mage::getModel('digidennis_dimensionit/slot')->load($value, 'option_type_id');
                    //do value have a valid slot
                    if( $slot && !is_null($slot->getPrice()) )
                    {
                        $posteddimensions = Mage::helper('digidennis_dimensionit/dimension')->getPostedDimensionsFromItem($this);
                        $processedForm = Mage::helper('digidennis_dimensionit/calc')->calculate(Mage::helper('digidennis_dimensionit/calc')->processFormular($posteddimensions, $slot->getPrice()));
                        $newprice = floor(floatval($_result->getPrice())*$processedForm);

                        //clamp to min or max
                        if(!is_null($slot->getMin()) && $newprice < floatval($slot->getMin()))
                            $newprice = floatval($slot->getMin());
                        elseif(!is_null($slot->getMax()) && $newprice > floatval($slot->getMax()))
                            $newprice = floatval($slot->getMax());

                        $result += $newprice;
                    }
                    else
                    {
                        $result += $this->_getChargableOptionPrice(
                            $_result->getPrice(),
                            $_result->getPriceType() == 'percent',
                            $basePrice
                        );
                    }
                }
                else
                {
                    if ($this->getListener()) {
                        $this->getListener()
                            ->setHasError(true)
                            ->setMessage(
                                $this->_getWrongConfigurationMessage()
                            );
                        break;
                    }
                }
            }
        }
        elseif ($this->_isSingleSelection())
        {
            if ($_result = $option->getValueById($optionValue))
            {
                $slot = Mage::getModel('digidennis_dimensionit/slot')->load($optionValue, 'option_type_id');

                if( $slot && !is_null($slot->getPrice()) )
                {
                    $posteddimensions = Mage::helper('digidennis_dimensionit/dimension')->getPostedDimensionsFromItem($this);
                    $processedForm = Mage::helper('digidennis_dimensionit/calc')->calculate(Mage::helper('digidennis_dimensionit/calc')->processFormular($posteddimensions, $slot->getPrice()));
                    $newprice = floor(floatval($_result->getPrice())*$processedForm);

                    //clamp to min or max
                    if(!is_null($slot->getMin()) && $newprice < floatval($slot->getMin()))
                        $newprice = floatval($slot->getMin());
                    elseif(!is_null($slot->getMax()) && $newprice > floatval($slot->getMax()))
                        $newprice = floatval($slot->getMax());

                    $result += $newprice;
                }
                else
                {
                    $result = $this->_getChargableOptionPrice(
                        $_result->getPrice(),
                        $_result->getPriceType() == 'percent',
                        $basePrice
                    );
                }
            }
            else
            {
                if ($this->getListener())
                {
                    $this->getListener()
                        ->setHasError(true)
                        ->setMessage(
                            $this->_getWrongConfigurationMessage()
                        );
                }
            }
        }

        return $result;
    }
}