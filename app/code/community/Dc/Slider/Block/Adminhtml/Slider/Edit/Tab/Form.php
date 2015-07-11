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
 * @copyright  Copyright (c) 2009-2015 Damián Culotta. (http://www.damianculotta.com.ar/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Dc_Slider_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_form', array('legend'=>Mage::helper('slider')->__('Slide information')));

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('slider')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));

        $fieldset->addField('position', 'text', array(
            'label'     => Mage::helper('slider')->__('Position'),
            'class'     => 'required-entry validate-number validate-greater-than-zero',
            'required'  => true,
            'name'      => 'position',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('slider')->__('Store View'),
                'title'     => Mage::helper('slider')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        } else {
            $model = Mage::registry('slider_data');
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('slider')->__('Status'),
            'required'  => true,
            'name'      => 'status',
            'values'    => array(
                                array(
                                        'value' => Dc_Slider_Model_Status::STATUS_ENABLED,
                                        'label' => Mage::helper('slider')->__('Enabled'),
                                    ),
                                array(
                                        'value' => Dc_Slider_Model_Status::STATUS_DISABLED,
                                        'label' => Mage::helper('slider')->__('Disabled'),
                                    ),
                                ),
        ));

        $fieldset->addField('filename', 'image', array(
            'label'     => Mage::helper('slider')->__('Filename'),
            'required'  => true,
            'name'      => 'filename',
        ));

        $fieldset->addField('url', 'text', array(
            'label'     => Mage::helper('slider')->__('Url'),
            'name'      => 'url',
        ));

        $date_format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('from_date', 'date', array(
            'label'     => Mage::helper('slider')->__('From'),
            'name'      => 'from_date',
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'    => $date_format
        ));

        $fieldset->addField('to_date', 'date', array(
            'label'     => Mage::helper('slider')->__('To'),
            'name'      => 'to_date',
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'    => $date_format
        ));
        
        if (Mage::getSingleton('adminhtml/session')->getSliderData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSliderData());
            Mage::getSingleton('adminhtml/session')->setSliderData(null);
        } elseif (Mage::registry('slider_data')) {
            $form->setValues(Mage::registry('slider_data')->getData());
        }

        return parent::_prepareForm();
    }

}
