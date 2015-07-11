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

class Dc_Slider_Model_Status extends Varien_Object
{
    
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;

    /**
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED  => Mage::helper('slider')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('slider')->__('Disabled')
        );
    }

}