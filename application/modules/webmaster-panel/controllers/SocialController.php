<?php

class WebmasterPanel_SocialController extends Zend_Controller_Action {

    public function indexAction() {
        $dbTableSocial = new Default_Model_DbTable_Social();
        $social = $dbTableSocial->fetchAll(null, 'pos ASC');

        $this->view->social = $social;
    }

    public function createAction() {
        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Social('Add');
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_created'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableSocial = new Default_Model_DbTable_Social();

                // check if position is set, if not then set this position to the highest position + 10
                if ($data['pos'] == '') {
                    $social = $dbTableSocial->fetchAll(null, 'pos DESC');
                    if (isset($social[0])) {
                        $data['pos'] = $social[0]->pos + 10;
                    } else {
                        $data['pos'] = 10;
                    }
                }

                // check if icon is set, if not then set it to default_image.png
                if ($data['icon'] == '') {
                    $data['icon'] = 'default_image.png';
                }

                $dbTableSocial->insert($data);
                $this->_redirect('/webmaster-panel/social/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        // controlleer of er een (social)id meegestuurd word
        $socialid = $this->_getParam('id', false);
        if ($socialid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/webmaster-panel/social/');
        }

        // create object and fetch row of the current social
        $dbTableSocial = new Default_Model_DbTable_Social();
        $social = $dbTableSocial->fetchRow($dbTableSocial->select()->where('id=' . $socialid));

        // check if object contains id
        if (!isset($social->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This social doesn't exists!";
            $this->_redirect('/webmaster-panel/social/');
        }

        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Social('Update', $social);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableSocial = new Default_Model_DbTable_Social();
                $dbTableSocial->update($data, "`id`='$socialid'");
                $this->_redirect('/webmaster-panel/social/');
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $dbTableSocial = new Default_Model_DbTable_Social();
        if (is_array($_POST['delete'])) {
            foreach ($_POST['delete'] as $key => $value) {
                if ($value == 'Y') {
                    $dbTableSocial->delete('id = ' . $key);
                }
            }
        }

        $this->_redirect('/webmaster-panel/social/');
    }

    public function updateAction() {
        $dbTableSocial = new Default_Model_DbTable_Social();
        if (is_array($_POST['update'])) {
            foreach ($_POST['update'] as $key => $value) {
                $dbTableSocial->update(array(
                    'pos' => $value,
                    'status' => $_POST['status'][$key],
                ), 'id = ' . $key);
            }
        }

        $this->_redirect('/webmaster-panel/social/');
    }

}

