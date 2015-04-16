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

class Dc_Slider_Block_Widget extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    
    protected $_items = array();
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('dc/slider/slider.phtml');
    }

    public function _getSlides()
    {
        $_slides = Mage::getModel('slider/slider')->getCollection()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->addFieldToFilter('status', array('eq' => Dc_Slider_Model_Status::STATUS_ENABLED))
                        ->addFieldToFilter('from_date', array(
                                                array('from_date', 'null' => true),
                                                array('from_date', 'lteq' => now())
                                ))
                        ->addFieldToFilter('to_date', array(
                                                array('to_date', 'null' => true),
                                                array('to_date', 'gteq' => now())
                                ))
                        ->setOrder('position', 'asc');
        return $_slides;
    }
    
    protected function _toHtml()
    {
        $this->assign('slider_type', Mage::app()->getStore()->getConfig('slider/javascript/plugin'));
        $this->assign('width', Mage::app()->getStore()->getConfig('slider/image/width'));
        $this->assign('height', Mage::app()->getStore()->getConfig('slider/image/height'));
        $this->assign('slides', $this->_getSlides());
        return parent::_toHtml();
    }

}
