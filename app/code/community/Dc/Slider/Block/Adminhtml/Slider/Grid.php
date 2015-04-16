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

class Dc_Slider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slider_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('slider/slider')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('slider_id', array(
            'header'    => Mage::helper('slider')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'slider_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('slider')->__('Name'),
            'index'     => 'name',
        ));

        $this->addColumn('filename', array(
            'header'    => Mage::helper('slider')->__('Filename'),
            'align'     =>'center',
            'width'     => '50px',
            'filter'    => false,
            'sortable'  => false,
            'renderer'  => 'slider/adminhtml_renderer_image',
        ));

        $this->addColumn('url', array(
            'header'    => Mage::helper('slider')->__('Url'),
            'align'     =>'left',
            'index'     => 'url',
            'renderer'  => 'slider/adminhtml_renderer_url',
        ));

        $this->addColumn('from_date', array(
            'header'    => Mage::helper('slider')->__('From'),
            'type'      => 'date',
            'index'     => 'from_date',
        ));

        $this->addColumn('to_date', array(
            'header'    => Mage::helper('slider')->__('To'),
            'type'      => 'date',
            'index'     => 'to_date',
        ));

        $this->addColumn('position', array(
            'header'    => Mage::helper('slider')->__('Position'),
            'align'     =>'right',
            'index'     => 'position',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('slider')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('status', array(
            'header'    => Mage::helper('slider')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getModel('slider/status')->getOptionArray()
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('slider')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('slider')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('slider_id');
        $this->getMassactionBlock()->setFormFieldName('slider');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('slider')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('slider')->__('Are you sure?')
        ));
        $statuses = Mage::getSingleton('slider/status')->getOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('slider')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                   'visibility' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('slider')->__('Status'),
                        'values' => $statuses
                    )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
