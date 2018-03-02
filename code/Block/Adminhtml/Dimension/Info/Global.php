<?php
/**
 * Created by PhpStorm.
 * User: W10
 * Date: 21-02-2018
 * Time: 00:37
 */
class Digidennis_DimensionIt_Block_Adminhtml_Dimension_Info_Global extends Mage_Adminhtml_Block_Template
{

    private $_foundoption;

    /**
     * Get order item object from parent block
     *
     * @return Mage_Sales_Model_Order_Item
     */
    public function getItem()
    {
        return $this->getParentBlock()->getData('item');
    }

    public function hasDimensionOutput()
    {
        $item = $this->getItem();
        $addoptions = $item->getOptionByCode('additional_options');
        if($addoptions){
            $addoptions = unserialize($addoptions->getValue());
            foreach ($addoptions as $option ){
                if($option['code'] == 'dimension_total'){
                    $this->_foundoption = $option;
                    return true;
                }
            }
        }
        return false;
    }

    public function getDimensionOutputHtml()
    {
        if($this->_foundoption){
            return "<label>{$this->_foundoption['label']}:</label> {$this->_foundoption['value']}";
        }
        return '';
    }

}