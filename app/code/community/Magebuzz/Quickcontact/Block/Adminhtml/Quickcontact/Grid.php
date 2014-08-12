<?php

class Magebuzz_Quickcontact_Block_Adminhtml_Quickcontact_Grid extends Mage_Adminhtml_Block_Widget_Grid {
  public function __construct() {
    parent::__construct();
    $this->setId('quickcontactGrid');
    $this->setDefaultSort('id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(TRUE);
  }

  protected function _prepareCollection() {
    $collection = Mage::getModel('quickcontact/quickcontact')->getCollection();
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns() {
    $this->addColumn('id', array('header' => Mage::helper('quickcontact')->__('ID'), 'align' => 'right', 'width' => '50px', 'index' => 'id',));

    $this->addColumn('name', array('header' => Mage::helper('quickcontact')->__('Customer Name'), 'align' => 'left', 'index' => 'name',));
    $this->addColumn('email', array('header' => Mage::helper('quickcontact')->__('Email'), 'align' => 'left', 'index' => 'email',));
    $this->addColumn('telephone', array('header' => Mage::helper('quickcontact')->__('Telephone'), 'align' => 'left', 'index' => 'telephone',));
    /*
      $this->addColumn('content', array(
      'header'    => Mage::helper('quickcontact')->__('Item Content'),
      'width'     => '150px',
      'index'     => 'content',
      ));
    */
    $this->addColumn('status', array('header' => Mage::helper('quickcontact')->__('Status'), 'align' => 'left', 'width' => '80px', 'index' => 'status', 'type' => 'options', 'options' => array(1 => 'Enabled', 2 => 'Disabled',),));

    $this->addColumn('action', array('header' => Mage::helper('quickcontact')->__('Action'), 'width' => '100', 'type' => 'action', 'getter' => 'getId', 'actions' => array(array('caption' => Mage::helper('quickcontact')->__('Edit'), 'url' => array('base' => '*/*/edit'), 'field' => 'id')), 'filter' => FALSE, 'sortable' => FALSE, 'index' => 'stores', 'is_system' => TRUE,));

    $this->addExportType('*/*/exportCsv', Mage::helper('quickcontact')->__('CSV'));
    $this->addExportType('*/*/exportXml', Mage::helper('quickcontact')->__('XML'));

    return parent::_prepareColumns();
  }

  protected function _prepareMassaction() {
    $this->setMassactionIdField('id');
    $this->getMassactionBlock()->setFormFieldName('quickcontact');

    $this->getMassactionBlock()->addItem('delete', array('label' => Mage::helper('quickcontact')->__('Delete'), 'url' => $this->getUrl('*/*/massDelete'), 'confirm' => Mage::helper('quickcontact')->__('Are you sure?')));

    $statuses = Mage::getSingleton('quickcontact/status')->getOptionArray();

    array_unshift($statuses, array('label' => '', 'value' => ''));
    $this->getMassactionBlock()->addItem('status', array('label' => Mage::helper('quickcontact')->__('Change status'), 'url' => $this->getUrl('*/*/massStatus', array('_current' => TRUE)), 'additional' => array('visibility' => array('name' => 'status', 'type' => 'select', 'class' => 'required-entry', 'label' => Mage::helper('quickcontact')->__('Status'), 'values' => $statuses))));
    return $this;
  }

  public function getRowUrl($row) {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}