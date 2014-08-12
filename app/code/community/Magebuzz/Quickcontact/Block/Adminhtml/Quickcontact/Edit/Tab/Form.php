<?php

class Magebuzz_Quickcontact_Block_Adminhtml_Quickcontact_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
  protected function _prepareForm() {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('quickcontact_form', array('legend' => Mage::helper('quickcontact')->__('Contact Information')));

    $fieldset->addField('name', 'text', array('label' => Mage::helper('quickcontact')->__('Customer Name'), 'class' => 'required-entry', 'required' => TRUE, 'name' => 'name',));
    $fieldset->addField('email', 'text', array('label' => Mage::helper('quickcontact')->__('Email'), 'class' => 'required-entry', 'required' => TRUE, 'name' => 'email',));
    $fieldset->addField('telephone', 'text', array('label' => Mage::helper('quickcontact')->__('Telephone'), 'required' => FALSE, 'name' => 'telephone',));
    $fieldset->addField('comment', 'textarea', array('label' => Mage::helper('quickcontact')->__('Comment'), 'required' => TRUE, 'style' => 'width:500px; height:150px;', 'name' => 'comment',));
    $fieldset->addField('status', 'select', array('label' => Mage::helper('quickcontact')->__('Status'), 'name' => 'status', 'values' => array(array('value' => 1, 'label' => Mage::helper('quickcontact')->__('Enabled'),),

      array('value' => 2, 'label' => Mage::helper('quickcontact')->__('Disabled'),),),));
    if (Mage::getSingleton('adminhtml/session')->getQuickcontactData()) {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getQuickcontactData());
      Mage::getSingleton('adminhtml/session')->setQuickcontactData(null);
    } elseif (Mage::registry('quickcontact_data')) {
      $form->setValues(Mage::registry('quickcontact_data')->getData());
    }
    return parent::_prepareForm();
  }
}