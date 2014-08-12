<?php

class Magebuzz_Quickcontact_Block_Quickcontact extends Mage_Core_Block_Template {
  public function _prepareLayout() {
    return parent::_prepareLayout();
  }

  public function getQuickcontact() {
    if (!$this->hasData('quickcontact')) {
      $this->setData('quickcontact', Mage::registry('quickcontact'));
    }
    return $this->getData('quickcontact');

  }

  public function leftSidebarBlock() {
    $block = $this->getParentBlock();
    if ($block) {
      if (Mage::helper('quickcontact')->displayOnSideBar() == 'left') {
        $sidebarBlock = $this->getLayout()->createBlock('quickcontact/quickcontactsidebar');
        $block->insert($sidebarBlock, '', TRUE, 'quick-contact-sidebar');
      }
    }
  }

  public function rightSidebarBlock() {
    $block = $this->getParentBlock();
    if ($block) {
      if (Mage::helper('quickcontact')->displayOnSideBar() == 'right') {
        $sidebarBlock = $this->getLayout()->createBlock('quickcontact/quickcontactsidebar');

        $block->insert($sidebarBlock, '', TRUE, 'quick-contact-sidebar');
      }
    }
  }
}