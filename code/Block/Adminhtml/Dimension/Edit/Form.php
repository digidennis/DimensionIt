<?php
class Digidennis_DimensionIt_Block_Adminhtml_Dimension_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('digidennis_dimensionit_dimension_form');
        $this->setTitle($this->__('Dimension Details'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('digidennis_dimensionit_dimension');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/savedimension', array('dimension_id' => $this->getRequest()->getParam('dimension_id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('checkout')->__('Dimension Details'),
            'class'     => 'fieldset-wide',
        ));

        if ($model->getDimensionId()) {
            $fieldset->addField('dimension_id', 'hidden', array(
                'name' => 'dimension_id',
            ));
        }

        if ($model->getSlotId()) {
            $fieldset->addField('slot_id', 'hidden', array(
                'name' => 'slot_id',
            ));
        }

        $fieldset->addField('label', 'text', array(
            'name'      => 'Label',
            'label'     => Mage::helper('checkout')->__('Label'),
            'title'     => Mage::helper('checkout')->__('Label'),
            'required'  => true,
        ));

        $fieldset->addField('unit', 'text', array(
            'name'      => 'Unit',
            'label'     => Mage::helper('checkout')->__('Unit'),
            'title'     => Mage::helper('checkout')->__('Unit'),
            'required'  => true,
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}