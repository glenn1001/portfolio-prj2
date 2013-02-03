<?php

class WebmasterPanel_ProjectController extends Zend_Controller_Action {

    public function indexAction() {
        $dbTableProject = new Default_Model_DbTable_Project();
        $projects = $dbTableProject->fetchAll(null, 'pos DESC');

        $this->view->projects = $projects;
    }

    public function createAction() {
        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Project('Add');
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_created'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableProject = new Default_Model_DbTable_Project();
                
                // check if position is set, if not then set this position to the highest position + 10
                if ($data['pos'] == '') {
                    $projects = $dbTableProject->fetchAll(null, 'pos DESC');
                    if (isset($projects[0])) {
                        $data['pos'] = $projects[0]->pos + 10;
                    } else {
                        $data['pos'] = 10;
                    }
                }
                
                // check if img is set, if not then set it to default_image.png
                if ($data['img'] == '') {
                    $data['img'] = 'default_image.png';
                }

                $dbTableProject->insert($data);
                
                $rewrite = new Portfolio_UrlRewrites();
                $rewrite->rewrite();
                
                $generate = new Portfolio_GenerateNav();
                $generate->generate();
                
                $this->_redirect('/webmaster-panel/project/');
            }
        }
        $this->view->form = $form;
    }

    public function editAction() {
        // controlleer of er een (project)id meegestuurd word
        $projectid = $this->_getParam('id', false);
        if ($projectid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/webmaster-panel/project/');
        }
        
        // create object and fetch row of the current project
        $dbTableProject = new Default_Model_DbTable_Project();
        $project = $dbTableProject->fetchRow($dbTableProject->select()->where('id=' . $projectid));

        // check if object contains id
        if (!isset($project->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This project doesn't exists!";
            $this->_redirect('/webmaster-panel/project/');
        }
        
        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Project('Update', $project);
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $data['date_modified'] = date('Y-m-d H:i:s');

                $dbTableProject = new Default_Model_DbTable_Project();
                $dbTableProject->update($data, "`id`='$projectid'");
                
                $rewrite = new Portfolio_UrlRewrites();
                $rewrite->rewrite();
                
                $generate = new Portfolio_GenerateNav();
                $generate->generate();
                
                $this->_redirect('/webmaster-panel/project/');
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {
        $dbTableProject = new Default_Model_DbTable_Project();
        if (is_array($_POST['delete'])) {
            foreach ($_POST['delete'] as $key => $value) {
                if ($value == 'Y') {
                    $dbTableProject->delete('id = ' . $key);
                }
            }
        }
        
        $rewrite = new Portfolio_UrlRewrites();
        $rewrite->rewrite();
        
        $generate = new Portfolio_GenerateNav();
        $generate->generate();

        $this->_redirect('/webmaster-panel/project/');
    }

    public function updateAction() {
        $dbTableProject = new Default_Model_DbTable_Project();
        if (is_array($_POST['update'])) {
            foreach ($_POST['update'] as $key => $value) {
                $dbTableProject->update(array(
                    'pos' => $value,
                    'status' => $_POST['status'][$key],
                    'menu' => $_POST['menu'][$key]
                        ), 'id = ' . $key);
            }
        }
        
        $generate = new Portfolio_GenerateNav();
        $generate->generate();

        $this->_redirect('/webmaster-panel/project/');
    }

}

