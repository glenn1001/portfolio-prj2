<?php

class Default_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->view->headMeta()->appendName('description', 'Portfolio website van Glenn Blom. Gebouwd in Zend Framework.');
        $this->view->headMeta()->appendName('keywords', 'Zend, Framework, Portfolio, Glenn, Blom');
        $this->view->headLink(array(
            'rel' => 'canonical',
            'href' => 'http://' . $this->getRequest()->getHttpHost() . '/'
        ));
    }

}

