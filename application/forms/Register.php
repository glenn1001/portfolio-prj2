<?php

class Application_Form_Register extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $this->setAttrib('action', 'save');
        $this->addElement('text', 'name', array(
            'label' => 'First and lastname:',
            'required' => true
        ));
        $this->addElement('text', 'email', array(
            'label' => 'E-mail address:',
            'required' => true,
            'filter' => array('StringTrim'),
            'validators' => array('EmailAddress')
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password:',
            'required' => true
        ));
        $this->addElement('submit', 'Save');
    }

}

