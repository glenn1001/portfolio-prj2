<?php

class WebmasterPanel_Form_Social extends Zend_Form {

    private $_id_value = '';
    private $_icon_value = '';
    private $_title_value = '';
    private $_url_value = '';
    private $_pos_value = '';
    private $_status_value = '';

    private function setValues($data) {
        $this->_id_value = $data->id;
        $this->_icon_value = $data->icon;
        $this->_title_value = $data->title;
        $this->_url_value = $data->url;
        $this->_pos_value = $data->pos;
        $this->_status_value = $data->status;
    }

    public function __construct($submitLabel, $data = null, $option = null) {
        parent::__construct($option);

        if ($data != null) {
            $this->setValues($data);
        }

        $this->setName('project');

        $icon = new Zend_Form_Element_Text('icon');
        $icon->setLabel('Image:')
                ->setValue($this->_icon_value);

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title:')
                ->setRequired(true)
                ->setFilters(array('StringTrim'))
                ->setValue($this->_title_value);

        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('URL:')
                ->setFilters(array('StringTrim'))
                ->setValue($this->_url_value);

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

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($submitLabel);

        $this->addElements(array($icon, $title, $url, $pos, $status, $submit));
        $this->setMethod('post');
        $this->setAction('');
    }

}
