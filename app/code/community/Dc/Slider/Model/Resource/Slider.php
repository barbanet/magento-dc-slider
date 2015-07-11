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

class Dc_Slider_Model_Resource_Slider extends Mage_Core_Model_Resource_Db_Abstract
{

    public function _construct()
    {
        $this->_init('slider/slider', 'slider_id');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
                            ->from($this->getTable('slider/slider_store'))
                            ->where('slider_id = ?', $object->getId());
        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }
        return parent::_afterLoad($object);
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('slider_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('slider/slider_store'), $condition);
        foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['slider_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('slider/slider_store'), $storeArray);
        }
        return parent::_afterSave($object);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param Mage_Core_Model_Abstract $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $select->join(
                        array('ss' => $this->getTable('slider/slider_store')),
                        $this->getMainTable().'.slider_id = `ss`.slider_id'
                    )
                    ->where('status=1 AND `ss`.store_id in (0, ?) ', $object->getStoreId())
                    ->order('store_id DESC')
                    ->limit(1);
        }
        return $select;
    }

}
