<?php

class Admn_Form_Page extends Zend_Form {

    private $_id_value = '';
    private $_title_value = '';
    private $_parent_id_value = '';
    private $_descr_value = '';
    private $_meta_descr_value = '';
    private $_meta_keywords_value = '';
    private $_canonical_value = '';
    private $_pos_value = '';
    private $_status_value = '';
    private $_menu_value = '';

    private function setValues($data) {
        $this->_id_value = $data->id;
        $this->_title_value = $data->title;
        $this->_parent_id_value = $data->parent_id;
        $this->_descr_value = $data->descr;
        $this->_meta_descr_value = $data->meta_descr;
        $this->_meta_keywords_value = $data->meta_keywords;
        $this->_canonical_value = $data->canonical;
        $this->_pos_value = $data->pos;
        $this->_status_value = $data->status;
        $this->_menu_value = $data->menu;
    }

    public function __construct($submitLabel, $pages, $data = null, $option = null) {
        parent::__construct($option);

        if ($data != null) {
            $this->setValues($data);
        }

        $this->setName('page');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->setValue($this->_title_value);

        $parent_id = new Zend_Form_Element_Select('parent_id');
        $parent_id = $this->setParentOptions($parent_id, $pages);
        $parent_id->setLabel('Parent page:')
                ->setValue($this->_parent_id_value)
                ->setValidators(array('Int'));

        $descr = new Zend_Form_Element_Textarea('descr');
        $descr->setLabel('Description:')
                ->setAttrib('id', 'descr')
                ->setValue($this->_descr_value);

        $meta_descr = new Zend_Form_Element_Textarea('meta_descr');
        $meta_descr->setLabel('META description:')
                ->setFilters(array('StringTrim'))
                ->setAttrib('cols', '50')
                ->setAttrib('rows', '5')
                ->setValue($this->_meta_descr_value);

        $meta_keywords = new Zend_Form_Element_Textarea('meta_keywords');
        $meta_keywords->setLabel('META keywords:')
                ->setFilters(array('StringTrim'))
                ->setAttrib('cols', '50')
                ->setAttrib('rows', '5')
                ->setValue($this->_meta_keywords_value);

        $canonical = new Zend_Form_Element_Text('canonical');
        $canonical->setLabel('Canonical URL:')
                ->setFilters(array('StringTrim'))
                ->setValue($this->_canonical_value);

        $pos = new Zend_Form_Element_Text('pos');
        $pos->setLabel('Position:')
                ->setValidators(array('Int'))
                ->setValue($this->_pos_value);

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status:')
                ->addMultiOptions(array(
                    'Y' => 'Active',
                    'N' => 'Inactive'
                ))
                ->setValue($this->_status_value);

        $menu = new Zend_Form_Element_Select('menu');
        $menu->setLabel('Show in navigation:')
                ->addMultiOptions(array(
                    'Y' => 'Yes',
                    'N' => 'No'
                ))
                ->setValue($this->_menu_value);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($submitLabel);

        $this->addElements(array($title, $parent_id, $descr, $meta_descr, $meta_keywords, $canonical, $pos, $status, $menu, $submit));
        $this->setMethod('post');
        $this->setAction('');
    }

    private function setParentOptions($element, $pages) {
        $element->addMultiOptions(array(
            0 => 'None'
        ));
        
        if (is_array($pages)) {
            foreach ($pages as $page) {
                if ($this->_id_value != $page->id && $this->_id_value != $page->parent_id) {
                    $element->addMultiOptions(array(
                        $page->id => $page->path
                    ));
                }
            }
        }

        return $element;
    }

}

