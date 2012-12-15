<?php

class Plugin_Navigation extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $registry = Zend_Registry::getInstance();
        $acl = $registry->get('acl');
        $view = $registry->get('view');
        
        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/modules/' . $request->getModuleName() . '/configs/navigation.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);

        $view->navigation($navContainer)->setAcl($acl)->setRole(Zend_Registry::get('role'));
    }

}