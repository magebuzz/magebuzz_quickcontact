<?php

class Magebuzz_Quickcontact_Helper_Data extends Mage_Core_Helper_Abstract {
  const XML_PATH_ENABLE_QUICKCONTACT = 'quickcontact/general/enabled';
  const XML_PATH_ENABLE_SIDEBAR = 'quickcontact/general/enabled_sidebar';
  const XML_PATH_ENABLE_CAPTCHA = 'quickcontact/general/enabled_captcha';
  const XML_PATH_SIDEBAR_POSITION = 'quickcontact/general/position';

  const XML_PATH_EMAIL_ADMIN_SENDER = 'quickcontact/email_setting/email_admin_sender';
  const XML_PATH_EMAIL_CUSTOMER_SENDER = 'quickcontact/email_setting/email_customers_sender';
  const XML_PATH_RECEPTION_EMAIL_ADMIN = 'quickcontact/email_setting/recipient_admin_email';
  const XML_PATH_EMAIL_ADMIN_TEMPLATE = 'quickcontact/email_setting/email_admin_template';
  const XML_PATH_EMAIL_CUSTOMER_TEMPLATE = 'quickcontact/email_setting/email_customers_template';

  public function isEnableQuickContact() {
    return (string)Mage::getStoreConfig(self::XML_PATH_ENABLE_QUICKCONTACT);
  }

  public function isEnableSidebar() {
    return (string)Mage::getStoreConfig(self::XML_PATH_ENABLE_SIDEBAR);
  }

  public function isEnableCaptcha() {
    return (string)Mage::getStoreConfig(self::XML_PATH_ENABLE_CAPTCHA);
  }

  public function displayOnSideBar() {
    return (string)Mage::getStoreConfig(self::XML_PATH_SIDEBAR_POSITION);
  }

  public function emailAdminSender() {
    return (string)Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_SENDER);
  }

  public function emailCustomerSender() {
    return (string)Mage::getStoreConfig(self::XML_PATH_EMAIL_CUSTOMER_SENDER);
  }

  public function receptionEmailAdmin() {
    return (string)Mage::getStoreConfig(self::XML_PATH_RECEPTION_EMAIL_ADMIN);
  }

  public function emailAdminTempate() {
    return (string)Mage::getStoreConfig(self::XML_PATH_EMAIL_ADMIN_TEMPLATE);
  }

  public function emailCustomerTempate() {
    return (string)Mage::getStoreConfig(self::XML_PATH_EMAIL_CUSTOMER_TEMPLATE);
  }

  public function isValidCaptcha() {
    $captchaCode = trim($_SESSION['captcha_code']);
    $captchaText = trim($_POST['captcha_text']);
    if (strtolower($captchaCode) != strtolower($captchaText)) {
      $_SESSION['recaptcha_error'] = 'Incorrect Text';
      return FALSE;
    } else {
      return TRUE;
    }
  }


}