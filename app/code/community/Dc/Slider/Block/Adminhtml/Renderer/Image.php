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
 
class Dc_Slider_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
    public function render(Varien_Object $row)
    {
        return ($this->_getContent($row));
    }
    
    protected function _getContent(Varien_Object $row)
    {
        $slide = null;
        $_filename = 'thumbnails' . DS . pathinfo($row->filename, PATHINFO_BASENAME);
        $_path_slider = Mage::app()->getStore()->getConfig('slider/options/folder') . DS;
        $slide = $row->filename != '' ? '<img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $_path_slider . $_filename.'" alt="" />' : '';
        return $slide;
    }

}
