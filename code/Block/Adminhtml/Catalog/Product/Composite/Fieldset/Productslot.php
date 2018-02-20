<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml block for fieldset of product custom options
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Composite_Fieldset_Productslot extends Mage_Adminhtml_Block_Catalog_Product_Composite_Fieldset_Options
{
    public function getProductslotDimensions()
    {
        $dimensionhelper = Mage::helper('digidennis_dimensionit/dimension');
        return $dimensionhelper->getDimensionsForProductSlot($this->getProduct());
    }

    public function getDimensionValue($dimension)
    {
        $quoteitems = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getAllItems();
        foreach ($quoteitems as $quoteitem ){
            if($quoteitem->getItemId() == $this->getRequest()->getParam('id')){
                $postedimensions = $quoteitem->getOptionByCode('posteddimensions');
                if($postedimensions){
                    $postedimensions = unserialize($postedimensions->getValue());
                    if(key_exists($dimension->getDimensionId(), $postedimensions)){
                        return $postedimensions[$dimension->getDimensionId()]['value'];
                    }
                }
            }
        }
        return $dimension->getInitial();
    }
}
