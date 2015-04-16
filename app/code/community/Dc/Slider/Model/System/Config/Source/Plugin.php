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

class Dc_Slider_Model_System_Config_Source_Plugin
{
    
    const RWD = '0';
    const BXSLIDER = '1';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::RWD,
                'label' => Mage::helper('slider')->__('RWD')
            ),
            array(
                'value' => self::BXSLIDER,
                'label' => Mage::helper('slider')->__('bxSlider')
            )
        );
    }

}