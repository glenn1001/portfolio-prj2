<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    private $_acl = null;

    protected function _initAutoload() {
        $modelLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH . '/modules/default'
                ));

        return $modelLoader;
    }

    protected function _initPlugins() {
        // Access plugin
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Plugin_LayoutCheck());
    }

}

