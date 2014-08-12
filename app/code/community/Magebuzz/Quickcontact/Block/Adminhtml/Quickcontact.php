<?php

class Magebuzz_Quickcontact_Block_Adminhtml_Quickcontact extends Mage_Adminhtml_Block_Widget_Grid_Container {
  public function __construct() {
    $this->_controller = 'adminhtml_quickcontact';
    $this->_blockGroup = 'quickcontact';
    $this->_headerText = Mage::helper('quickcontact')->__('Manage Contacts');
    $this->_addButtonLabel = Mage::helper('quickcontact')->__('Add New Contact');
    parent::__construct();
  }
}