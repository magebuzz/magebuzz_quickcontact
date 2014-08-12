<?php

class Magebuzz_Quickcontact_Model_Mysql4_Quickcontact_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
  public function _construct() {
    parent::_construct();
    $this->_init('quickcontact/quickcontact');
  }
}