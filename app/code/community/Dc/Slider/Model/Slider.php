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

class Dc_Slider_Model_Slider extends Mage_Core_Model_Abstract
{

    /**
     * @var
     */
    protected $_images_path;

    /**
     * @var
     */
    protected $_thumbnail_path;

    public function _construct()
    {
        parent::_construct();
        $this->_init('slider/slider');
    }

    /**
     * @param $path
     * @return bool
     */
    private function _checkFolderExists($path)
    {
        try {
            $ioProxy = new Varien_Io_File();
            $ioProxy->setAllowCreateFolders(true);
            $ioProxy->open(array($path));
            $ioProxy->close();
            unset($ioProxy);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Returns slider images directory
     *
     * @return string
     */
    private function _getImagesPath()
    {
        if (!$this->_images_path) {
            $this->_images_path = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Mage::app()->getStore()->getConfig('slider/options/folder') . DS;
        }
        return $this->_images_path;
    }

    /**
     * Returns slider thumbnails directory
     *
     * @return string
     */
    private function _getThumbnailsPath()
    {
        if (!$this->_thumbnail_path) {
            $this->_thumbnail_path = $this->_getImagesPath() . 'thumbnails' . DS;
        }
        return $this->_thumbnail_path;
    }

    /**
     * @param $source
     * @return bool|string
     */
    public function createThumbnail($source)
    {
        if (!is_file($this->_getImagesPath() . $source) || !is_readable($this->_getImagesPath() . $source)) {
            return false;
        }
        $io = new Varien_Io_File();
        if (!$io->isWriteable($this->_getThumbnailsPath())) {
            $io->mkdir($this->_getThumbnailsPath());
        }
        if (!$io->isWriteable($this->_getThumbnailsPath())) {
            return false;
        }
        $image = Varien_Image_Adapter::factory('GD2');
        $image->open($this->_getImagesPath() . $source);
        $width = Mage::app()->getStore()->getConfig('slider/thumbnail/width');
        $height = Mage::app()->getStore()->getConfig('slider/thumbnail/height');
        $image->keepAspectRatio(true);
        $image->resize($width, $height);
        $destination = $this->_getThumbnailsPath() . pathinfo($source, PATHINFO_BASENAME);
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
        if (!is_file($this->_getImagesPath() . pathinfo($image, PATHINFO_BASENAME)) || !is_readable($this->_getImagesPath() . pathinfo($image, PATHINFO_BASENAME))) {
            return false;
        }
        @unlink($this->_getImagesPath()  . pathinfo($image, PATHINFO_BASENAME));
        @unlink($this->_getThumbnailsPath() . pathinfo($image, PATHINFO_BASENAME));
    }

    /**
     * @param $data
     * @return string
     */
    public function uploadImage($data)
    {
        if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
            $path = $this->_getImagesPath();
            $this->_checkFolderExists($path);
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