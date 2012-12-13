<?php

class Plugin_UrlRewrite extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // get and set current url
        $url = $this->getRequest()->getRequestUri();

        // check if string ends with a slash, redirect if it doesn't
        if (substr($url, strlen($url) - 1) != '/') {
            $this->_response->setRedirect($url . '/');
        }

        // set some dbTable
        $dbTableUrlRewrite = new Default_Model_DbTable_UrlRewrite();
        $dbTableUrlRewriteParameter = new Default_Model_DbTable_UrlRewriteParameter();

        // get url_rewrite data
        $url_rewrite = $dbTableUrlRewrite->fetchRow($dbTableUrlRewrite->select()->where("`url`='$url'"));

        if (!empty($url_rewrite)) {
            // set module, controller and action
            $request->setModuleName($url_rewrite->module);
            $request->setControllerName($url_rewrite->controller);
            $request->setActionName($url_rewrite->action);

            // get parameters
            $parameters = $dbTableUrlRewriteParameter->fetchAll("`id`='" . $url_rewrite->id . "'");

            // set parameters
            foreach ($parameters as $parameter) {
                $request->setParam($parameter->key, $parameter->value);
            }
        } else {
            // get current module, controller, action and parameters
            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();
            $parameters = $request->getParams();
            ksort($parameters);

            $url_rewrites = $dbTableUrlRewrite->fetchAll($dbTableUrlRewrite->select()->where("`module`='$module' AND `controller`='$controller' AND `action`='$action'"));
            
            // check if current url can be rewritten
            foreach ($url_rewrites as $url_rewrite) {
                $dbParameters = $dbTableUrlRewriteParameter->fetchAll("`id`='" . $url_rewrite->id . "'");
                $tmpArray = array(
                    'module' => $module,
                    'controller' => $controller,
                    'action' => $action
                );

                foreach ($dbParameters as $dbParameter) {
                    $tmpArray[$dbParameter->key] = $dbParameter->value;
                }
                ksort($tmpArray);

                if ($parameters === $tmpArray) {
                    $this->_response->setRedirect($url_rewrite->url);
                }
            }
        }
    }

}