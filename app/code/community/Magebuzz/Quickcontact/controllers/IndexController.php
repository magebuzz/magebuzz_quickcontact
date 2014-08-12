<?php

class Magebuzz_Quickcontact_IndexController extends Mage_Core_Controller_Front_Action {
  const XML_PATH_EMAIL_ADMIN_SENDER = 'quickcontact/email_setting/email_admin_sender';
  const XML_PATH_EMAIL_CUSTOMER_SENDER = 'quickcontact/email_setting/email_customers_sender';
  const XML_PATH_RECEPTION_EMAIL_ADMIN = 'quickcontact/email_setting/recipient_admin_email';
  const XML_PATH_EMAIL_ADMIN_TEMPLATE = 'quickcontact/email_setting/email_admin_template';
  const XML_PATH_EMAIL_CUSTOMER_TEMPLATE = 'quickcontact/email_setting/email_customers_template';

  public function indexAction() {
    $this->loadLayout();
    $this->_initLayoutMessages('customer/session');
    $this->_initLayoutMessages('catalog/session');
    $this->renderLayout();
  }

  public function postAction() {
    $post = $this->getRequest()->getPost();
    $currentUrl = $post['currentUrl'];
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

        if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
          $error = TRUE;
        }

        if ($error) {
          throw new Exception();
        }

        //Send mail to admin
        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))->setReplyTo($post['email'])->sendTransactional(Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_SENDER), Mage::getStoreConfig(self::XML_PATH_RECEPTION_EMAIL_ADMIN), null, array('data' => $postObject));
        if (!$mailTemplate->getSentSuccess()) {
          throw new Exception();
        }
        $translate->setTranslateInline(TRUE);

        //Send mail to customer

        $customerEmail = $post['email'];

        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(FALSE);

        $mailTemplate = Mage::getModel('core/email_template');

        $mailTemplate->setDesignConfig(array('area' => 'frontend'))->setReplyTo($post['email'])->sendTransactional(Mage::getStoreConfig(self::XML_PATH_EMAIL_CUSTOMER_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_CUSTOMER_SENDER), $customerEmail, null, array('data' => $postObject));

        if (!$mailTemplate->getSentSuccess()) {

          throw new Exception();
        }

        $translate->setTranslateInline(TRUE);

        //save to database
        $model = Mage::getModel('quickcontact/quickcontact');

        if (!$model) {
          die('Could not get model');
        }
        try {
          $model->setData($post);
          $model->setCreatedTime(strtotime('now'));
          $model->setUpdateTime(strtotime('now'));
          $model->save();
        } catch (Exception $e) {
          echo $e->getMessage();
        }

        Mage::getSingleton('core/session')->addSuccess(Mage::helper('quickcontact')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
        Mage::app()->getResponse()->setRedirect($currentUrl);

        return;
      } catch (Exception $e) {
        $translate->setTranslateInline(TRUE);

        Mage::getSingleton('customer/session')->addError(Mage::helper('quickcontact')->__('Unable to submit your request. Please, try again later'));
        Mage::app()->getResponse()->setRedirect($currentUrl);
        return;
      }

    } else {
      $this->_redirect('*/*/');
    }
  }

  public function imageCaptchaAction() {
    require_once(Mage::getBaseDir('lib') . DS . 'captcha' . DS . 'class.simplecaptcha.php');
    $config['BackgroundImage'] = Mage::getBaseDir('lib') . DS . 'captcha' . DS . "white.png";
    $config['BackgroundColor'] = "FF0000";
    $config['Height'] = 30;
    $config['Width'] = 100;
    $config['Font_Size'] = 23;
    $config['Font'] = Mage::getBaseDir('lib') . DS . 'captcha' . DS . "ARLRDBD.TTF";
    $config['TextMinimumAngle'] = 0;
    $config['TextMaximumAngle'] = 0;
    $config['TextColor'] = '000000';
    $config['TextLength'] = 4;
    $config['Transparency'] = 80;
    $captcha = new SimpleCaptcha($config);
    $_SESSION['captcha_code'] = $captcha->Code;
  }

  public function refreshcaptchaAction() {
    $result = Mage::getModel('core/url')->getUrl('*/*/imageCaptcha/') . rand(5, 20);
    echo $result;
  }
}