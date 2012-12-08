<?php

class TestController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $form = new Application_Form_Register();
        $this->view->form = $form;
    }

    public function chatAction()
    {
        $this->view->assign('msg', 'Hello world');
    }

    public function otherAction()
    {
        
    }

    public function insertAction()
    {
        $register = new Application_Model_Register();
        $register->createUser(array(
            'naam' => 'Glenn',
            'age' => 22,
            'gender' => 'm'
        ));
    }

    public function testAction()
    {
        // action body
    }

    public function updateAction()
    {
        $register = new Application_Model_Register();
        /*$register->updateUser(array(
            'naam' => 'Piet',
            'age' => 202,
            'gender' => 'm'
        ), array(
            'naam' => 'Glenn',
            'age' => 23
        ));*/
        
        $register->updateUser(array(
            'naam' => 'Piet',
            'age' => 20,
            'gender' => 'm'
        ), 2);
    }


}



