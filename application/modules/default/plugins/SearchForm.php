<?php

class Plugin_SearchForm extends Zend_Controller_Plugin_Abstract {

    private $_view;

    public function __construct() {
        $registry = Zend_Registry::getInstance();
        $this->_view = $registry->get('view');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $post = $request->getPost();
        $form = new Default_Form_Search($post['search_keywords']);
        $this->_view->searchForm = $form;
    }

}

?>
