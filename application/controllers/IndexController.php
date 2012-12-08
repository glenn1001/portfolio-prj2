<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $head_info = array(
            'title' => 'Portfolio - Glenn Blom',
            'meta_description' => 'Portfolio website van Glenn Blom. Gebouwd in Zend Framework',
            'meta_keywords' => 'Zend, Framework, Portfolio, Glenn, Blom',
            'canonical' => 'http://portfolio-prj2.glennblom.nl/'
        );
        
        $this->view->assign('head_info', $head_info);
    }

}

