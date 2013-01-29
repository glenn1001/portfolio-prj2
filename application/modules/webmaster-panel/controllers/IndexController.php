<?php

class WebmasterPanel_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $dbTableText = new Default_Model_DbTable_Text();
        $text = $dbTableText->fetchAll(null, 'pos ASC');

        $this->view->text = $text;
    }

    public function createAction() {
        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Text('Add');
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_created'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableText = new Default_Model_DbTable_Text();

                // check if position is set, if not then set this position to the highest position + 10
                if ($data['pos'] == '') {
                    $text = $dbTableText->fetchAll(null, 'pos DESC');
                    if (isset($text[0])) {
                        $data['pos'] = $text[0]->pos + 10;
                    } else {
                        $data['pos'] = 10;
                    }
                }

                $dbTableText->insert($data);
                $this->_redirect('/webmaster-panel/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        // controlleer of er een (text)id meegestuurd word
        $textid = $this->_getParam('id', false);
        if ($textid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/webmaster-panel/');
        }

        // create object and fetch row of the current text
        $dbTableText = new Default_Model_DbTable_Text();
        $text = $dbTableText->fetchRow($dbTableText->select()->where('id=' . $textid));

        // check if object contains id
        if (!isset($text->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This text doesn't exists!";
            $this->_redirect('/webmaster-panel/');
        }

        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Text('Update', $text);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableText = new Default_Model_DbTable_Text();
                $dbTableText->update($data, "`id`='$textid'");
                $this->_redirect('/webmaster-panel/');
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $dbTableText = new Default_Model_DbTable_Text();
        if (is_array($_POST['delete'])) {
            foreach ($_POST['delete'] as $key => $value) {
                if ($value == 'Y') {
                    $dbTableText->delete('id = ' . $key);
                }
            }
        }

        $this->_redirect('/webmaster-panel/');
    }

    public function updateAction() {
        $dbTableText = new Default_Model_DbTable_Text();
        if (is_array($_POST['update'])) {
            foreach ($_POST['update'] as $key => $value) {
                $dbTableText->update(array(
                    'pos' => $value,
                    'status' => $_POST['status'][$key],
                ), 'id = ' . $key);
            }
        }

        $this->_redirect('/webmaster-panel/');
    }
}