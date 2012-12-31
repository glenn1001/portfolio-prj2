<?php

class Admn_SlideshowController extends Zend_Controller_Action {

    public function indexAction() {
        $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
        $slideshow = $dbTableSlideshow->fetchAll(null, 'pos ASC');

        $this->view->slideshow = $slideshow;
    }

    public function createAction() {
        $request = $this->getRequest();
        $form = new Admn_Form_Slideshow('Add');
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_created'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableSlideshow = new Default_Model_DbTable_Slideshow();

                // check if position is set, if not then set this position to the highest position + 10
                if ($data['pos'] == '') {
                    $slideshow = $dbTableSlideshow->fetchAll(null, 'pos DESC');
                    if (isset($slideshow[0])) {
                        $data['pos'] = $slideshow[0]->pos + 10;
                    } else {
                        $data['pos'] = 10;
                    }
                }

                // check if img is set, if not then set it to default_image.png
                if ($data['img'] == '') {
                    $data['img'] = 'default_image.png';
                }

                $dbTableSlideshow->insert($data);
                $this->_redirect('/admn/slideshow/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        // controlleer of er een (slideshow)id meegestuurd word
        $slideshowid = $this->_getParam('id', false);
        if ($slideshowid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/admn/slideshow/');
        }

        // create object and fetch row of the current slideshow
        $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
        $slideshow = $dbTableSlideshow->fetchRow($dbTableSlideshow->select()->where('id=' . $slideshowid));

        // check if object contains id
        if (!isset($slideshow->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This slideshow doesn't exists!";
            $this->_redirect('/admn/slideshow/');
        }

        $request = $this->getRequest();
        $form = new Admn_Form_Slideshow('Update', $slideshow);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
                $dbTableSlideshow->update($data, "`id`='$slideshowid'");
                $this->_redirect('/admn/slideshow/');
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
        if (is_array($_POST['delete'])) {
            foreach ($_POST['delete'] as $key => $value) {
                if ($value == 'Y') {
                    $dbTableSlideshow->delete('id = ' . $key);
                }
            }
        }

        $this->_redirect('/admn/slideshow/');
    }

    public function updateAction() {
        $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
        if (is_array($_POST['update'])) {
            foreach ($_POST['update'] as $key => $value) {
                $dbTableSlideshow->update(array(
                    'pos' => $value,
                    'status' => $_POST['status'][$key],
                    'img_loc' => $_POST['img_loc'][$key]
                ), 'id = ' . $key);
            }
        }

        $this->_redirect('/admn/slideshow/');
    }

}

