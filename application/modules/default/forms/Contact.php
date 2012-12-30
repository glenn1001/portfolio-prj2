<?php

class Default_Form_Contact extends Zend_Form {
    
    public function __construct($option = null) {
        parent::__construct($option);
        
        $this->setName('contactform');

        $name = new Zend_Form_Element_Text('contactform_name');
        $name->setLabel('Naam:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->addErrorMessage('Naam is verplicht!');

        $email = new Zend_Form_Element_Text('contactform_email');
        $email->setLabel('E-mail:')
                ->setValidators(array('EmailAddress'))
                ->setRequired(true)
                ->addErrorMessage('Dit is geen correct e-mail adres!');
        
        $subject = new Zend_Form_Element_Text('contactform_subject');
        $subject->setLabel('Onderwerp:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->addErrorMessage('Onderwerp is verplicht!');
        
        $message = new Zend_Form_Element_Textarea('contactform_message');
        $message->setRequired(true)
                ->addErrorMessage('Bericht is verplicht!');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Verzenden');

        $this->addElements(array($name, $email, $subject, $message, $submit));
        $this->setMethod('post');
        $this->setAction('');
    }

}

