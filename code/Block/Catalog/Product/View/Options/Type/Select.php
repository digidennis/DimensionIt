<?php
/**
 * Product options text type block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Digidennis_DimensionIt_Block_Catalog_Product_View_Options_Type_Select
    extends Mage_Catalog_Block_Product_View_Options_Type_Select
{
    /**
     * Return html for control element
     *
     * @return string
     */
    public function getValuesHtml()
    {
        $_option = $this->getOption();
        $_optionslots = Mage::helper('digidennis_dimensionit/slot')->getOptionSlots($_option);
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();

        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
            $require = ($_option->getIsRequire()) ? ' required-entry' : '';
            $extraParams = '';
            $select = $this->getLayout()->createBlock('core/html_select')
                ->setData(array(
                    'id' => 'select_'.$_option->getId(),
                    'class' => $require.' product-custom-option'
                ));
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_DROP_DOWN) {
                $select->setName('options['.$_option->getid().']')
                    ->addOption('', $this->__('-- Please Select --'));
            } else {
                $select->setName('options['.$_option->getid().'][]');
                $select->setClass('multiselect'.$require.' product-custom-option');
            }
            foreach ($_option->getValues() as $_value) {
                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice(($_value->getPriceType() == 'percent'))
                ), false);
                $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . $priceStr . '',
                    array('price' => $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false))
                );
            }
            if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE) {
                $extraParams = ' multiple="multiple"';
            }
            if (!$this->getSkipJsReloadPrice()) {
                $extraParams .= ' onchange="opConfig.reloadPrice()"';
            }
            $select->setExtraParams($extraParams);

            if ($configValue) {
                $select->setValue($configValue);
            }

            $dimensionhtml = '';
            if( $_optionslots && $_optionslots->getFirstItem() )
            {
                $dimensionhtml .= "<ul class='dimensionlist'>";
                $dimensions = $_optionslots->getFirstItem()->getDimensions();
                foreach( $dimensions as $dimension )
                {
                    $dimensionhtml .= "<li>";
                    $dimensionhtml .= "<label for='dimension_{$dimension->getId()}'>{$dimension->getLabel()}</label>";
                    $dimensionhtml .= "<input class=\"product-custom-option dimensionit\" id=\"dimension_{$dimension->getId()}\" name=\"dimensions[{$dimension->getId()}][value]\" value=\"{$dimension->getInitial()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][label]\" value=\"{$dimension->getLabel()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][unit]\" value=\"{$dimension->getUnit()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][output]\" value=\"{$dimension->getOutput()}\">";
                    $dimensionhtml .= "</li>";
                }
                $dimensionhtml .= "</ul>";
            }

            return $select->getHtml() . $dimensionhtml;
        }

        if ($_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO
            || $_option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX
        ) {
            $selectHtml = '<ul id="options-'.$_option->getId().'-list" class="options-list">';
            $require = ($_option->getIsRequire()) ? ' validate-one-required-by-name' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_RADIO:
                    $type = 'radio';
                    $class = 'radio';
                    if (!$_option->getIsRequire()) {
                        $selectHtml .= '<li><input type="radio" id="options_' . $_option->getId() . '" class="'
                            . $class . ' product-custom-option" name="options[' . $_option->getId() . ']"'
                            . ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"')
                            . ' value="" checked="checked" /><span class="label"><label for="options_'
                            . $_option->getId() . '">' . $this->__('None') . '</label></span></li>';
                    }
                    break;
                case Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX:
                    $type = 'checkbox';
                    $class = 'checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 1;
            foreach ($_option->getValues() as $_value) {
                $count++;

                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent')
                ));

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = (is_array($configValue) && in_array($htmlValue, $configValue)) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }

                $selectHtml .= '<li>' . '<input type="' . $type . '" class="' . $class . ' ' . $require
                    . ' product-custom-option"'
                    . ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"')
                    . ' name="options[' . $_option->getId() . ']' . $arraySign . '" id="options_' . $_option->getId()
                    . '_' . $count . '" value="' . $htmlValue . '" ' . $checked . ' price="'
                    . $this->helper('core')->currencyByStore($_value->getPrice(true), $store, false) . '" />'
                    . '<span class="label"><label for="options_' . $_option->getId() . '_' . $count . '">'
                    . $this->escapeHtml($_value->getTitle()) . ' ' . $priceStr . '</label></span>';
                if ($_option->getIsRequire()) {
                    $selectHtml .= '<script type="text/javascript">' . '$(\'options_' . $_option->getId() . '_'
                        . $count . '\').advaiceContainer = \'options-' . $_option->getId() . '-container\';'
                        . '$(\'options_' . $_option->getId() . '_' . $count
                        . '\').callbackFunction = \'validateOptionsCallback\';' . '</script>';
                }
                $selectHtml .= '</li>';

                /**
                 * Dimension It add
                 */
                $optiontypeslot = Mage::helper('digidennis_dimensionit/slot')->getOptionTypeSlot($_value->getOptionTypeId());
                if($optiontypeslot->getSlotId())
                {
                    $selectHtml .= "<li>";
                    $dimensions = $optiontypeslot->getDimensions();
                    foreach( $dimensions as $dimension )
                    {
                        $selectHtml .= "<li>";
                        $selectHtml .= "<label for='dimension_{$dimension->getId()}'>{$dimension->getLabel()}</label>";
                        $selectHtml .= "<input class=\"product-custom-option dimensionit\" id=\"dimension_{$dimension->getId()}\" name=\"dimensions[{$dimension->getId()}][value]\" value=\"{$dimension->getInitial()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][label]\" value=\"{$dimension->getLabel()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][unit]\" value=\"{$dimension->getUnit()}\">
<input type=\"hidden\" name=\"dimensions[{$dimension->getId()}][output]\" value=\"{$dimension->getOutput()}\">";
                        $selectHtml .= "</li>";
                    }
                }
                // END Dimension It Add

            }
            $selectHtml .= '</ul>';

            return $selectHtml;
        }
    }
}
