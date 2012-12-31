<?php

class Default_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $this->view->headMeta()->appendName('description', 'Portfolio website van Glenn Blom. Gebouwd in Zend Framework.');
        $this->view->headMeta()->appendName('keywords', 'Zend, Framework, Portfolio, Glenn, Blom');
        $this->view->headLink(array(
            'rel' => 'canonical',
            'href' => 'http://' . $this->getRequest()->getHttpHost() . '/'
        ));
        
        $dbTableSlideshow = new Default_Model_DbTable_Slideshow();
        $slideshow = $dbTableSlideshow->fetchAll("`status`='Y'", 'pos ASC');

        $this->view->slideshow = $slideshow;
    }

}

