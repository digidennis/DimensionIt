<?php

class Digidennis_DimensionIt_Adminhtml_DimensionitController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
            ->renderLayout();
    }

    public function newdimensionAction()
    {
        // We just forward the new action to a blank edit form
        $this->_forward('editdimension');
    }

    public function editdimensionAction()
    {
        // Get id if available
        $id  = $this->getRequest()->getParam('dimension_id');
        $model = Mage::getModel('digidennis_dimensionit/dimension');

        if ($id) {
            // Load record
            $model->load($id);

            // Check if record is loaded
            if (!$model->getDimensionId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Dimension no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getLabel() : $this->__('New Dimension'));

        $data = Mage::getSingleton('adminhtml/session')->getDimensionData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('digidennis_dimensionit_dimension', $model);

        $this->_initAction()->renderLayout();
    }

    public function savedimensionAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton('digidennis_dimensionit/dimension');
            $model->setData($postData);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Dimension has been saved.'));
                $this->_redirect('*/*/');

                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving dimension.'));
            }

            Mage::getSingleton('adminhtml/session')->setDimensionData($postData);
            $this->_redirectReferer();
        }
    }

    public function messageAction()
    {
        $data = Mage::getModel('digidennis_dimensionit/dimension')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            // Make the active menu match the menu config nodes (without 'children' inbetween)
            ->_setActiveMenu('catalog/product')
            ->_title($this->__('DimensionIt'))->_title($this->__('DimensionIt'))
            ->_addBreadcrumb($this->__('Catalog'), $this->__('Catalog'))
            ->_addBreadcrumb($this->__('DimensionIt'), $this->__('DimensionIt'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/digidennis_dimensionit');
    }
}