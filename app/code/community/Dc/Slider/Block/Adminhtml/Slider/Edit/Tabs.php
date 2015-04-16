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

class Dc_Slider_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('slider')->__('Slider Information'));
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('slider')->__('Slide Information'),
            'title'     => Mage::helper('slider')->__('Slide Information'),
            'content'   => $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
