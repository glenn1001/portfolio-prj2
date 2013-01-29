<?php

class Plugin_Social extends Zend_Controller_Plugin_Abstract {

    private $_view;

    public function __construct() {
        $registry = Zend_Registry::getInstance();
        $this->_view = $registry->get('view');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $dbTableSocial = new Default_Model_DbTable_Social();
        $social = $dbTableSocial->fetchAll("`status`='Y'", 'pos ASC');
        
        $this->_view->social = $social;
    }

}