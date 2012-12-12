<?php

class Admn_Form_Login extends Zend_Form {

    public function __construct($option = null) {
        parent::__construct($option);

        $this->setName('login');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('E-mail address:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->setValidators(array('EmailAddress'));

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'));

        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login');

        $this->addElements(array($email, $password, $login));
        $this->setMethod('post');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/admn/auth/login/');
    }

}