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

class Dc_Slider_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'slider';
        $this->_controller = 'adminhtml_slider';
        $this->_updateButton('save', 'label', Mage::helper('slider')->__('Save Slide'));
        $this->_updateButton('delete', 'label', Mage::helper('slider')->__('Delete Slide'));

    }

    public function getHeaderText()
    {
        if (Mage::registry('slider_data') && Mage::registry('slider_data')->getId()) {
            return Mage::helper('slider')->__('Edit Slide');
        } else {
            return Mage::helper('slider')->__('Add Slide');
        }
    }

}
