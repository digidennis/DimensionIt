<?php

$installer = $this;
$installer->startSetup();

$slottable = $installer->getConnection()->newTable( $installer->getTable('digidennis_dimensionit/slot'))
    ->addColumn('slot_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Slot Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false), 'Product ID')
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 0, array(
        'unsigned' => true,
        'nullable' => true), 'Option Type ID')
    ->addForeignKey(
        $installer->getFkName('digidennis_dimensionit/slot', 'product_id', 'catalog/product', 'entity_id'),
        'product_id',
        $installer->getTable('catalog/product'),
        'entity_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );
$installer->getConnection()->createTable($slottable);

$dimtable = $installer->getConnection()->newTable( $installer->getTable('digidennis_dimensionit/dimension'))
    ->addColumn('dimension_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Dimension ID')
    ->addColumn('slot_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false), 'Slot ID')
    ->addColumn('output', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array('default' => true,), 'Output value')
    ->addColumn('step', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'default' => 0.5,
        'precision' => 10,
        'scale' => 1,
        ), 'Step value')
    ->addColumn('min', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'nullable' => true,
        'precision' => 10,
        'scale' => 1,
        ), 'Minimum Value')
    ->addColumn('max', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'nullable' => true,
        'precision' => 10,
        'scale' => 1,
        ), 'Maximum Value')
    ->addColumn('initial', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'nullable' => false,
        'precision' => 10,
        'scale' => 1,
        ), 'Maximum Value')
    ->addColumn('unit', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false,), 'Unit')
    ->addColumn('label', Varien_Db_Ddl_Table::TYPE_TEXT, null, array('nullable' => false,), 'Label')
    ->addForeignKey(
        $installer->getFkName('digidennis_dimensionit/dimension', 'slot_id', 'digidennis_dimensionit/slot', 'slot_id'),
        'slot_id',
        $installer->getTable('digidennis_dimensionit/slot'),
        'slot_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );
$installer->getConnection()->createTable($dimtable);

$transformertable = $installer->getConnection()->newTable( $installer->getTable('digidennis_dimensionit/transformer'))
    ->addColumn('transfomer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Transformer ID')
    ->addColumn('slot_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 0, array(
        'unsigned' => true,
        'nullable' => false), 'Slot ID')
    ->addColumn('min', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'nullable' => true,
        'precision' => 10,
        'scale' => 1,
        ), 'Minimum Value')
    ->addColumn('max', Varien_Db_Ddl_Table::TYPE_DECIMAL, null, array(
        'nullable' => true,
        'precision' => 10,
        'scale' => 1,
        ), 'Maximum Value')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Price Formular')
    ->addColumn('weight', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Weight Formular')
    ->addForeignKey(
        $installer->getFkName('digidennis_dimensionit/transformer', 'slot_id', 'digidennis_dimensionit/slot', 'slot_id'),
        'slot_id',
        $installer->getTable('digidennis_dimensionit/slot'),
        'slot_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );
$installer->getConnection()->createTable($transformertable);
$installer->endSetup();
