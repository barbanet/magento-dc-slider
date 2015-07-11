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

class Dc_Slider_Model_Slider extends Mage_Core_Model_Abstract
{

    /**
     * @var
     */
    protected $cache_path;

    /**
     * @var
     */
    protected $images_path;

    /**
     * @var
     */
    protected $thumbnail_path;

    public function _construct()
    {
        parent::_construct();
        $this->_init('slider/slider');
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
            return false;
        }
    }

    /**
     * Returns slider cache directory
     *
     * @return string
     */
    private function getCachePath()
    {
        if (!$this->cache_path) {
            $width = Mage::app()->getStore()->getConfig('slider/image/width');
            $height = Mage::app()->getStore()->getConfig('slider/image/height');
            $this->cache_path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Mage::app()->getStore()->getConfig('slider/options/folder') . DS . 'cache' . DS . $width . 'x' . $height . DS;
        }
        return $this->cache_path;
    }

    /**
     * Returns slider images directory
     *
     * @return string
     */
    private function getImagesPath()
    {
        if (!$this->images_path) {
            $this->images_path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Mage::app()->getStore()->getConfig('slider/options/folder') . DS;
        }
        return $this->images_path;
    }

    /**
     * Returns slider thumbnails directory
     *
     * @return string
     */
    private function getThumbnailsPath()
    {
        if (!$this->thumbnail_path) {
            $this->thumbnail_path = $this->getImagesPath() . 'thumbnails' . DS;
        }
        return $this->thumbnail_path;
    }

    /**
     * @param $source
     * @return bool|string
     */
    public function createThumbnail($source)
    {
        if (!is_file($this->getImagesPath() . $source) || !is_readable($this->getImagesPath() . $source)) {
            return false;
        }
        $io = new Varien_Io_File();
        if (!$io->isWriteable($this->getThumbnailsPath())) {
            $io->mkdir($this->getThumbnailsPath());
        }
        if (!$io->isWriteable($this->getThumbnailsPath())) {
            return false;
        }
        $image = Varien_Image_Adapter::factory('GD2');
        $image->open($this->getImagesPath() . $source);
        $width = Mage::app()->getStore()->getConfig('slider/thumbnail/width');
        $height = Mage::app()->getStore()->getConfig('slider/thumbnail/height');
        $image->keepAspectRatio(true);
        $image->resize($width, $height);
        $destination = $this->getThumbnailsPath() . pathinfo($source, PATHINFO_BASENAME);
        $image->save($destination);
        if (is_file($destination)) {
            return $destination;
        }
        return false;
    }

    /**
     * @param $image
     * @return bool
     */
    public function deleteImage($image)
    {
        if (!is_file($this->getImagesPath() . pathinfo($image, PATHINFO_BASENAME)) || !is_readable($this->getImagesPath() . pathinfo($image, PATHINFO_BASENAME))) {
            return false;
        }
        @unlink($this->getImagesPath()  . pathinfo($image, PATHINFO_BASENAME));
        @unlink($this->getCachePath()  . pathinfo($image, PATHINFO_BASENAME));
        @unlink($this->getThumbnailsPath() . pathinfo($image, PATHINFO_BASENAME));
    }

    /**
     * @param $data
     * @return string
     */
    public function uploadImage($data)
    {
        if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
            $path = $this->getImagesPath();
            $this->checkFolderExists($path);
            if (isset($data['filename']['value'])) {
                $this->deleteImage($data['filename']['value']);
            }
            $uploader = new Varien_File_Uploader('filename');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->save($path, $uploader->getUploadedFileName());
            $data['filename'] = Mage::app()->getStore()->getConfig('slider/options/folder') . DS . $uploader->getUploadedFileName();
            $this->createThumbnail($uploader->getUploadedFileName());
        } elseif (isset($data['filename']['delete']) && $data['filename']['delete'] == '1') {
            $this->deleteImage($data['filename']['value']);
            $data['filename'] = '';
        } else {
            if (empty($data['filename'])) {
                return false;
            } else {
                $data['filename'] = $data['filename']['value'];
            }
        }
        return $data['filename'];
    }
    
}
