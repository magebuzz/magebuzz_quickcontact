<?php

class Magebuzz_Quickcontact_Model_Observer extends Varien_Object {
  public function quickContactObserver($observer) {
    $controller = $observer->getControllerAction();
    $post = $controller->getRequest()->getPost();
    if ($post) {
      if (isset($post['check'])) {
        if (Mage::getStoreConfig('quickcontact/general/enabled_captcha') == 1) {
          if (!Mage::helper('quickcontact')->isValidCaptcha()) {
            Mage::getSingleton('adminhtml/session')->addError('Invalid captcha!');
            Mage::getSingleton('adminhtml/session')->setFormData($post);
            $url = Mage::app()->getResponse()->setRedirect(Mage::getUrl("quickcontact/"));
            return;
          }
        }
      }
    }
    if ($post) {
      $translate = Mage::getSingleton('core/translate');
      $translate->setTranslateInline(FALSE);
      try {
        $postObject = new Varien_Object();
        $postObject->setData($post);

        $error = FALSE;

        if (!Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
          $error = TRUE;
        }

        if (!Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
          $error = TRUE;
        }

        if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
          $error = TRUE;
        }
        if ($error) {
          throw new Exception();
        }
        $model = Mage::getModel('quickcontact/quickcontact');
        $model->setData($post);
        $model->save();
        $translate->setTranslateInline(TRUE);
        //Mage::getSingleton('core/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
        //Mage::app()->getResponse()->setRedirect(Mage::getUrl("quickcontact/index/"));
        return;
      } catch (Exception $e) {
        $translate->setTranslateInline(TRUE);
        Mage::getSingleton('core/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
        Mage::app()->getResponse()->setRedirect(Mage::getUrl("quickcontact/index/"));
        return;
      }

    }
  }
}