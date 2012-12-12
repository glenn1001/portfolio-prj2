<?php

class Default_PageController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        // controlleer of er een (page)id meegestuurd word
        $pageid = $this->_getParam('id', false);
        if ($pageid == false) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = 'Er is geen id meegestuurd als parameter!';
            $this->_redirect('/');
        }

        // create object and fetch row of the current page
        $dbTablePage = new Default_Model_DbTable_Page();
        $page = $dbTablePage->fetchRow($dbTablePage->select()->where('id=' . $pageid));

        // check if object contains id
        if (!isset($page->id)) {
            $error = new Zend_Session_Namespace('error');
            $error->msg = 'De pagina die u probeerde aan te roepen bestaat niet!';
            $this->_redirect('/admn/page/');
        }
        
        $this->view->pageinfo = $page;
    }

}

