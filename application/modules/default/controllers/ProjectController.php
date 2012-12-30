<?php

class Default_ProjectController extends Zend_Controller_Action {
    
    private $_pageId;
    private $_totalPages;

    public function indexAction() {
        $this->_pageId = $this->_getParam('pageid', 1);
        $dbTableProject = new Default_Model_DbTable_Project();
        
        $totalProjects = count($dbTableProject->fetchAll());
        $this->_totalPages = ceil($totalProjects / $dbTableProject->projectsPerPage);
        
        if ($this->_pageId > $this->_totalPages) {
            $this->redirect('/project/index/pageid/' . $this->_totalPages . '/');
        }
        
        $projects = $dbTableProject->getProjects($this->_pageId);
        
        $this->view->projects = $projects;
        
        $this->view->currentPage = $this->_pageId;
        $this->view->prevPages = $this->getPrevPages();
        $this->view->pages = $this->getPages();
        $this->view->nextPages = $this->getNextPages();
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
    
    private function getPrevPages() {
        $pages = array();
        if ($this->_pageId > 1) {
            $pages[] = array(
                'url'   => '/projecten/',
                'name'  => 'Eerste pagina'
            );
            
            $url = '/projecten/' . (($this->_pageId - 1) == 1 ? '' : ($this->_pageId - 1). '/');
            $pages[] = array(
                'url'   => $url,
                'name'  => 'Vorige pagina'
            );
        }
        
        return $pages;
    }
    
    private function getPages() {
        $pages = array();
        $start = $this->_pageId - 2;
        $end = $this->_pageId + 2;
        
        if ($end > $this->_totalPages) {
            $end = $this->_totalPages;
        }
        
        for ($i = $start; $i <= $end ;$i++) {
            if ($i >= 1) {
                $pages[] = array(
                    'url'   => '/projecten/' . $i . '/',
                    'name'  => $i
                );
            }
        }
        
        return $pages;
    }
    
    private function getNextPages() {
        $pages = array();
        if (($this->_pageId + 1) <= $this->_totalPages) {
            $pages[] = array(
                'url'   => '/projecten/' . ($this->_pageId + 1) . '/',
                'name'  => 'Volgende pagina'
            );
            $pages[] = array(
                'url'   => '/projecten/' . $this->_totalPages . '/',
                'name'  => 'Laatste pagina'
            );
        }
        
        return $pages;
    }

}

