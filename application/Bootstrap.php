<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_acl = null;

    protected function _initAutoload() {
        $modelLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH . '/modules/default'
                ));
        
        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);
        } else {
            Zend_Registry::set('role', 'guest');
        }
        
        $this->_acl = new Model_Acl();

        return $modelLoader;
    }

    protected function _initPlugins() {
        // Access plugin
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Plugin_LayoutCheck());
        $front->registerPlugin(new Plugin_UrlRewrite());
        $front->registerPlugin(new Plugin_AccessCheck($this->_acl));
    }
    
    protected function _initErrorHandling() {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Plugin_ErrorHandling());
        
        $registry = Zend_Registry::getInstance();
        
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $view->errorMsg = $registry->get('error');
    }
    
    protected function _initViewHelpers() {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $view->doctype('HTML5');
        $view->headMeta()->appendHttpEquiv('Content-type','text/html;charset=utf-8');
        
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Portfolio - Glenn Blom');
    }

}

