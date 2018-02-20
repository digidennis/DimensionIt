<?php
class Digidennis_DimensionIt_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{

    protected function _prepareColumns()
    {
        $this->addColumn('dimensionit',
            array(
                'header'    => Mage::helper('catalog')->__('DimIt'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url'     => array(
                            'base'=>'adminhtml/dimensionit/newdimension'
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            ));
        // show URL Key column after SKU column
        $this->addColumnsOrder('dimensionit', 'sku');

        return parent::_prepareColumns();
    }
}
?>