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

class Dc_Slider_Helper_Image extends Mage_Core_Helper_Abstract
{

    /**
     * Get cached image with the requested size.
     *
     * @param $image
     * @param $width
     * @param $height
     * @param bool $keep_ration
     * @return string
     */
    public function getImage($image, $width, $height, $keep_ration = true)
    {
        $media_path = Mage::getBaseDir('media');
        $cache_path = $media_path . Mage::app()->getStore()->getConfig('slider/options/folder') . DS . 'cache';
        $cache_url = Mage::app()->getStore()->getConfig('slider/options/folder') . DS . 'cache';
        $full_path = $media_path . Mage::app()->getStore()->getConfig('slider/options/folder') . $image;
        $image_name = pathinfo($full_path, PATHINFO_BASENAME);
        $cached_image = $cache_path . DS . $width . 'x' . $height . DS . $image_name;
        if (!file_exists($cached_image)) {
            $this->_resizeImage($image, $width, $height, $keep_ration);
        }
        return $cache_url . DS . $width . 'x' . $height . DS . $image_name;
    }

    /**
     * Resize image and create cached file with the new size.
     *
     * @param $source
     * @param $width
     * @param $height
     * @param bool $keep_ration
     * @return bool|string
     */
    private function _resizeImage($source, $width, $height, $keep_ration = true)
    {
        $path_media = Mage::getBaseDir('media');
        if (!is_file($path_media . $source) || !is_readable($path_media . $source)) {
            return false;
        }
        $path_image = Mage::app()->getStore()->getConfig('slider/options/folder');
        $path_cache = $path_media . $path_image . DS . 'cache' . DS . $width . 'x' . $height;
        $this->checkFolderExists($path_cache);
        $image = Varien_Image_Adapter::factory('GD2');
        $image->open($path_media . $source);
        $image->keepAspectRatio($keep_ration);
        $image->resize($width, $height);
        $_destination = $path_cache . DS . pathinfo($source, PATHINFO_BASENAME);
        $image->save($_destination);
        if (is_file($_destination)) {
            return $_destination;
        }
        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    private function checkFolderExists($path)
    {
        try {
            $io_proxy = new Varien_Io_File();
            $io_proxy->setAllowCreateFolders(true);
            $io_proxy->open(array($path));
            $io_proxy->close();
            unset($io_proxy);
            return true;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
    }

}
