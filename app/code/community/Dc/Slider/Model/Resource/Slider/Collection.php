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

class Dc_Slider_Model_Resource_Slider_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('slider/slider');
    }

    /**
     * @param $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }
        $this->getSelect()->join(
            array('store_table' => $this->getTable('slider/slider_store')),
            'main_table.slider_id = store_table.slider_id',
            array()
        )
        ->where('store_table.store_id in (?)', ($withAdmin ? array(0, $store) : $store))
        ->group('main_table.slider_id');
        return $this;
    }

}