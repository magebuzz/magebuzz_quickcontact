<?php

class Magebuzz_Quickcontact_Model_Mysql4_Quickcontact extends Mage_Core_Model_Mysql4_Abstract {
  public function _construct() {
    // Note that the quickcontact_id refers to the key field in your database table.
    $this->_init('quickcontact/quickcontact', 'id');
  }
}