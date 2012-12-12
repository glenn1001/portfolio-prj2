<?php

class Admn_Form_Page extends Zend_Form {

    private $title_value = '';
    private $descr_value = '';
    private $head_title_value = '';
    private $meta_descr_value = '';
    private $meta_keywords_value = '';
    private $canonical_value = '';
    private $pos_value = '';
    private $status_value = '';
    private $menu_value = '';

    public function setValues($data) {
        $this->title_value = $data->title;
        $this->descr_value = $data->descr;
        $this->head_title_value = $data->head_title;
        $this->meta_descr_value = $data->meta_descr;
        $this->meta_keywords_value = $data->meta_keywords;
        $this->canonical_value = $data->canonical;
        $this->pos_value = $data->pos;
        $this->status_value = $data->status;
        $this->menu_value = $data->menu;
    }

    public function __construct($submitLabel, $data = null, $option = null) {
        parent::__construct($option);

        if ($data != null) {
            $this->setValues($data);
        }

        $this->setName('page');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Titel:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->setValue($this->title_value);

        $descr = new Zend_Form_Element_Textarea('descr');
        $descr->setLabel('Beschrijving:')
                ->setAttrib('id', 'descr')
                ->setValue($this->descr_value);

        $head_title = new Zend_Form_Element_Text('head_title');
        $head_title->setLabel('Titel in header:')
                ->setFilters(array('StringTrim'))
                ->setValue($this->head_title_value);

        $meta_descr = new Zend_Form_Element_Textarea('meta_descr');
        $meta_descr->setLabel('META beschrijving:')
                ->setFilters(array('StringTrim'))
                ->setAttrib('cols', '50')
                ->setAttrib('rows', '5')
                ->setValue($this->meta_descr_value);

        $meta_keywords = new Zend_Form_Element_Textarea('meta_keywords');
        $meta_keywords->setLabel('META keywords:')
                ->setFilters(array('StringTrim'))
                ->setAttrib('cols', '50')
                ->setAttrib('rows', '5')
                ->setValue($this->meta_keywords_value);

        $canonical = new Zend_Form_Element_Text('canonical');
        $canonical->setLabel('Canonical link:')
                ->setFilters(array('StringTrim'))
                ->setValue($this->canonical_value);

        $pos = new Zend_Form_Element_Text('pos');
        $pos->setLabel('Positie:')
                ->setValidators(array('Int'))
                ->setValue($this->pos_value);

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status:')
                ->addMultiOptions(array(
                    'Y' => 'Actief',
                    'N' => 'Inactief'
                ))
                ->setValue($this->status_value);;

        $menu = new Zend_Form_Element_Select('menu');
        $menu->setLabel('Toon in menu:')
                ->addMultiOptions(array(
                    'Y' => 'Ja',
                    'N' => 'Nee'
                ))
                ->setValue($this->menu_value);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($submitLabel);

        $this->addElements(array($title, $descr, $head_title, $meta_descr, $meta_keywords, $canonical, $pos, $status, $menu, $submit));
        $this->setMethod('post');
        $this->setAction('');
    }

}

