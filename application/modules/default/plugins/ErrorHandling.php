<?php

class Plugin_ErrorHandling extends Zend_Controller_Plugin_Abstract {
    public function __construct() {
        $error = new Zend_Session_Namespace('error');
        $registry = Zend_Registry::getInstance();
        
        if (isset($error->msg)) {
            $registry->set('error', $error->msg);
            $error->msg = '';
        } else {
            $registry->set('error', '');
        }
        
    }
}