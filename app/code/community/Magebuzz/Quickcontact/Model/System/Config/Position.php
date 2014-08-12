<?php

class Magebuzz_Quickcontact_Model_System_Config_Position {
  public function toOptionArray() {
    $options = array(array('value' => 'left', 'label' => Mage::helper('quickcontact')->__('Left Sidebar')), array('value' => 'right', 'label' => Mage::helper('quickcontact')->__('Right Sidebar')),);

    return $options;
  }
}