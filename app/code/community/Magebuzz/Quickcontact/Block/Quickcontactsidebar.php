<?php

class Magebuzz_Quickcontact_Block_Quickcontactsidebar extends Magebuzz_Quickcontact_Block_Quickcontact {
  public function _construct() {
    $this->setTemplate('quickcontact/sidebar.phtml');
    return parent::_construct();
  }

  public function _prepareLayout() {
    return parent::_prepareLayout();
  }
}

?>