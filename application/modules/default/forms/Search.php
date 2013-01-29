<?php

class Default_Form_Search extends Zend_Form
{

    public function __construct($search_keywords, $option = null) {
        parent::__construct($option);
        
        $this->setName('search');

        $name = new Zend_Form_Element_Text('search_keywords');
        $name->setFilters(array('StringTrim'))
                ->clearDecorators()
                ->setDecorators(array(
                    'ViewHelper',
                    'Label'
                ))
                ->setValue($search_keywords);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Zoeken')
                ->clearDecorators()
                ->setDecorators(array(
                    'ViewHelper'
                ));

        $this->addElements(array($name, $submit));
        $this->setMethod('post');
        $this->setAction('/search/');
    }


}

