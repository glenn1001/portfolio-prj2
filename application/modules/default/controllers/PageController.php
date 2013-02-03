<?php

class Default_PageController extends Zend_Controller_Action {

    public function indexAction() {
        // check if a page id has been set
        $pageid = $this->_getParam('id', false);
        if ($pageid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "Parameter 'id' is missing!";
            $this->_redirect('/');
        }

        // create object and fetch row of the current page
        $dbTablePage = new Default_Model_DbTable_Page();
        $page = $dbTablePage->fetchRow($dbTablePage->select()->where('id=' . $pageid));

        // check if object contains id
        if (!isset($page->id) || $page->status == 'N') {
            $error = new Zend_Session_Namespace('error');
            $error->msg = "This page doesn't exists!";
            $this->_redirect('/page-not-found/');
        }
        
        // set meta data for page
        $this->view->headTitle($page->title, 'PREPEND');
        if ($page->meta_descr != '') {
            $this->view->headMeta()->appendName('description', $page->meta_descr);
        }
        if ($page->meta_keywords != '') {
            $this->view->headMeta()->appendName('keywords', $page->meta_keywords);
        }
        if ($page->canonical != '') {
            $this->view->headLink(array(
                'rel' => 'canonical',
                'href' => 'http://' . $this->getRequest()->getHttpHost() . $page->canonical
            ));
        }
        
        $this->view->pageinfo = $page;
    }

}

