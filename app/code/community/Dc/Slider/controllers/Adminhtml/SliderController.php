<?php
/**
 * Dc_Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Dc
 * @package    Dc_Slider
 * @copyright  Copyright (c) 2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Slider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_action
{

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('cms/slider');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('slider/slider')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('slider_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('cms/slider');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('slider/adminhtml_slider_edit'))
                ->_addLeft($this->getLayout()->createBlock('slider/adminhtml_slider_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Slide does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            try {
                $data['filename'] = Mage::getModel('slider/slider')->uploadImage($data);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::logException($e);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            $model = Mage::getModel('slider/slider');        
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                if (!empty($data['from_date'])) {
                    $date_format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                    $date = Mage::app()->getLocale()->date($data['from_date'], $date_format);
                    $time = $date->getTimestamp();
                    $model->setFromDate(Mage::getSingleton('core/date')->gmtDate(null, $time));
                }
                if (!empty($data['to_date'])) {
                    $date_format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                    $date = Mage::app()->getLocale()->date($data['to_date'], $date_format);
                    $time = $date->getTimestamp();
                    $model->setToDate(Mage::getSingleton('core/date')->gmtDate(null, $time));
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('slider')->__('Slide was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('slider')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }
 
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('slider/slider')->load($this->getRequest()->getParam('id'));
                $model->deleteImage($model->getFilename());
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slide was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if(!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($sliderIds as $sliderId) {
                    $slider = Mage::getModel('slider/slider')->load($sliderId);
                    $slider->deleteImage($slider->getFilename());
                    $slider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($sliderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function massStatusAction()
    {
        $sliderIds = $this->getRequest()->getParam('slider');
        if(!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($sliderIds as $sliderId) {
                    Mage::getSingleton('slider/slider')
                        ->load($sliderId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($sliderIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}
