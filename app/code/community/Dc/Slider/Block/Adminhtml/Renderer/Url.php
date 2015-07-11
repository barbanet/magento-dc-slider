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
 
class Dc_Slider_Block_Adminhtml_Renderer_Url extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
    public function render(Varien_Object $row)
    {
        return ($this->_getContent($row));
    }
    
    protected function _getContent(Varien_Object $row)
    {
        return $row->url != '' ? '<a href="' . $row->url.'" target="_blank">' . $row->url . '</a>' : '';
    }

}
