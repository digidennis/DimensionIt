<?php
/**
 * Created by PhpStorm.
 * User: W10
 * Date: 21-02-2018
 * Time: 00:37
 */
class Digidennis_DimensionIt_Block_Adminhtml_Dimension_Info_Global extends Mage_Adminhtml_Block_Template
{
    /**
     * Get order item object from parent block
     *
     * @return Mage_Sales_Model_Order_Item
     */
    public function getItem()
    {
        return $this->getParentBlock()->getData('item');
    }

}