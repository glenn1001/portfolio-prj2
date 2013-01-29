<?php

class Plugin_FooterProjects extends Zend_Controller_Plugin_Abstract {

    private $_view;

    public function __construct() {
        $registry = Zend_Registry::getInstance();
        $this->_view = $registry->get('view');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $dbTableProject = new Default_Model_DbTable_Project();
        $projects = $dbTableProject->fetchAll("`status`='Y'", 'id DESC', 10);

        $this->_view->footerProjects = $projects;
    }

}