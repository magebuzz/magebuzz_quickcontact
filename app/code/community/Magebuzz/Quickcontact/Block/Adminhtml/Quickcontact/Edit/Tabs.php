<?php

class Magebuzz_Quickcontact_Block_Adminhtml_Quickcontact_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

  public function __construct() {
    parent::__construct();
    $this->setId('quickcontact_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('quickcontact')->__('Contact Information'));
  }

  protected function _beforeToHtml() {
    $this->addTab('form_section', array('label' => Mage::helper('quickcontact')->__('Contact Information'), 'title' => Mage::helper('quickcontact')->__('Contact Information'), 'content' => $this->getLayout()->createBlock('quickcontact/adminhtml_quickcontact_edit_tab_form')->toHtml(),));

    return parent::_beforeToHtml();
  }
}