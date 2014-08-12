<?php

class Magebuzz_Quickcontact_Adminhtml_QuickcontactController extends Mage_Adminhtml_Controller_Action {

  protected function _initAction() {
    $this->loadLayout()->_setActiveMenu('quickcontact/items')->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Contacts'), Mage::helper('adminhtml')->__('Manage Contacts'));

    return $this;
  }

  public function indexAction() {
    $this->_initAction()->renderLayout();
  }

  public function editAction() {
    $id = $this->getRequest()->getParam('id');
    $model = Mage::getModel('quickcontact/quickcontact')->load($id);

    if ($model->getId() || $id == 0) {
      $data = Mage::getSingleton('adminhtml/session')->getFormData(TRUE);
      if (!empty($data)) {
        $model->setData($data);
      }

      Mage::register('quickcontact_data', $model);

      $this->loadLayout();
      $this->_setActiveMenu('quickcontact/items');

      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Contacts'), Mage::helper('adminhtml')->__('Manage Contacts'));
      $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Contacts'), Mage::helper('adminhtml')->__('Manage Contacts'));

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(TRUE);

      $this->_addContent($this->getLayout()->createBlock('quickcontact/adminhtml_quickcontact_edit'))->_addLeft($this->getLayout()->createBlock('quickcontact/adminhtml_quickcontact_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('quickcontact')->__('Contact does not exist'));
      $this->_redirect('*/*/');
    }
  }

  public function newAction() {
    $this->_forward('edit');
  }

  public function saveAction() {
    if ($data = $this->getRequest()->getPost()) {
      $model = Mage::getModel('quickcontact/quickcontact');
      $model->setData($data)->setId($this->getRequest()->getParam('id'));

      try {
        if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
          $model->setCreatedTime(now())->setUpdateTime(now());
        } else {
          $model->setUpdateTime(now());
        }

        $model->save();
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('quickcontact')->__('Item was successfully saved'));
        Mage::getSingleton('adminhtml/session')->setFormData(FALSE);

        if ($this->getRequest()->getParam('back')) {
          $this->_redirect('*/*/edit', array('id' => $model->getId()));
          return;
        }
        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setFormData($data);
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('quickcontact')->__('Unable to find item to save'));
    $this->_redirect('*/*/');
  }

  public function deleteAction() {
    if ($this->getRequest()->getParam('id') > 0) {
      try {
        $model = Mage::getModel('quickcontact/quickcontact');

        $model->setId($this->getRequest()->getParam('id'))->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }

  public function massDeleteAction() {
    $quickcontactIds = $this->getRequest()->getParam('quickcontact');
    if (!is_array($quickcontactIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
    } else {
      try {
        foreach ($quickcontactIds as $quickcontactId) {
          $quickcontact = Mage::getModel('quickcontact/quickcontact')->load($quickcontactId);
          $quickcontact->delete();
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($quickcontactIds)));
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function massStatusAction() {
    $quickcontactIds = $this->getRequest()->getParam('quickcontact');
    if (!is_array($quickcontactIds)) {
      Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
    } else {
      try {
        foreach ($quickcontactIds as $quickcontactId) {
          $quickcontact = Mage::getSingleton('quickcontact/quickcontact')->load($quickcontactId)->setStatus($this->getRequest()->getParam('status'))->setIsMassupdate(TRUE)->save();
        }
        $this->_getSession()->addSuccess($this->__('Total of %d record(s) were successfully updated', count($quickcontactIds)));
      } catch (Exception $e) {
        $this->_getSession()->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  public function exportCsvAction() {
    $fileName = 'quickcontact.csv';
    $content = $this->getLayout()->createBlock('quickcontact/adminhtml_quickcontact_grid')->getCsv();

    $this->_sendUploadResponse($fileName, $content);
  }

  public function exportXmlAction() {
    $fileName = 'quickcontact.xml';
    $content = $this->getLayout()->createBlock('quickcontact/adminhtml_quickcontact_grid')->getXml();

    $this->_sendUploadResponse($fileName, $content);
  }

  protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
    $response = $this->getResponse();
    $response->setHeader('HTTP/1.1 200 OK', '');
    $response->setHeader('Pragma', 'public', TRUE);
    $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', TRUE);
    $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
    $response->setHeader('Last-Modified', date('r'));
    $response->setHeader('Accept-Ranges', 'bytes');
    $response->setHeader('Content-Length', strlen($content));
    $response->setHeader('Content-type', $contentType);
    $response->setBody($content);
    $response->sendResponse();
    die;
  }
}