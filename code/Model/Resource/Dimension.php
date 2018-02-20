<?php

class Digidennis_DimensionIt_Model_Resource_Dimension extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_dimensionit/dimension', 'dimension_id');
    }
}