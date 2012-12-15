<?php

class Default_ProjectController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $pageid = $this->_getParam('pageid', 1);
        $dbTableProject = new Default_Model_DbTable_Project();
        
        $totalProjects = count($dbTableProject->fetchAll());
        $totalPages = ceil($totalProjects / $dbTableProject->_projectsPerPage);
        
        if ($pageid > $totalPages) {
            $this->redirect('/project/index/pageid/' . $totalPages . '/');
        }
        
        $projects = $dbTableProject->getProjects($pageid);
        
        $this->view->projects = $projects;
    }

    public function viewAction() {
        // controlleer of er een (project)id meegestuurd word
        $projectid = $this->_getParam('id', false);
        if ($projectid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/');
        }

        // create object and fetch row of the current project
        $dbTableProject = new Default_Model_DbTable_Project();
        $project = $dbTableProject->fetchRow($dbTableProject->select()->where('id=' . $projectid));

        // check if object contains id
        if (!isset($project->id) || $project->status == 'N') {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This project doesn't exists!";
            $this->_redirect('/project-not-found/');
        }
        
        $this->view->headTitle($project->title, 'PREPEND');
        if ($project->meta_descr != '') {
            $this->view->headMeta()->appendName('description', $project->meta_descr);
        }
        if ($project->meta_keywords != '') {
            $this->view->headMeta()->appendName('keywords', $project->meta_keywords);
        }
        if ($project->canonical != '') {
            $this->view->headLink(array(
                'rel' => 'canonical',
                'href' => 'http://' . $this->getRequest()->getHttpHost() . '/project' . $project->canonical
            ));
        }
        
        $this->view->projectinfo = $project;
    }

}

