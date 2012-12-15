<?php

class Admn_PageController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $dbTablePage = new Default_Model_DbTable_Page();
        $pages = $dbTablePage->getPagesByParent();

        $this->view->pages = $pages;
    }

    public function createAction() {
        $dbTablePage = new Default_Model_DbTable_Page();
        $pages = $dbTablePage->getPagesByParent();
        
        $request = $this->getRequest();
        $form = new Admn_Form_Page('Add', $pages);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_created'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTablePage = new Default_Model_DbTable_Page();
                
                // check if position is set, if not then set this position to the highest position + 10
                if ($data['pos'] == '') {
                    $pages = $dbTablePage->fetchAll(null, 'pos DESC');
                    if (isset($pages[0])) {
                        $data['pos'] = $pages[0]->pos + 10;
                    } else {
                        $data['pos'] = 10;
                    }
                }

                $dbTablePage->insert($data);
                $this->_redirect('/admn/page/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        // controlleer of er een (page)id meegestuurd word
        $pageid = $this->_getParam('id', false);
        if ($pageid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/admn/page/');
        }
        
        // create object and fetch row of the current page
        $dbTablePage = new Default_Model_DbTable_Page();
        $page = $dbTablePage->fetchRow($dbTablePage->select()->where('id=' . $pageid));

        // check if object contains id
        if (!isset($page->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This page doesn't exists!";
            $this->_redirect('/admn/page/');
        }
        
        $pages = $dbTablePage->getPagesByParent();

        $request = $this->getRequest();
        $form = new Admn_Form_Page('Update', $pages, $page);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTablePage = new Default_Model_DbTable_Page();
                $dbTablePage->update($data, "`id`='$pageid'");
                $this->_redirect('/admn/page/');
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $dbTablePage = new Default_Model_DbTable_Page();
        if (is_array($_POST['delete'])) {
            foreach ($_POST['delete'] as $key => $value) {
                if ($value == 'Y') {
                    $dbTablePage->delete('id = ' . $key);
                }
            }
        }

        $this->_redirect('/admn/page/');
    }

    public function updateAction() {
        $dbTablePage = new Default_Model_DbTable_Page();
        if (is_array($_POST['update'])) {
            foreach ($_POST['update'] as $key => $value) {
                $dbTablePage->update(array(
                    'pos' => $value,
                    'status' => $_POST['status'][$key],
                    'menu' => $_POST['menu'][$key]
                        ), 'id = ' . $key);
            }
        }

        $this->_redirect('/admn/page/');
    }

}

