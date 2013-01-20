<?php

class Portfolio_HtmlTemplateMailer extends Zend_Mail {

    /**
     *
     * @var Zend_View
     */
    static $_defaultView;

    /**
     * current instance of Zend_View
     * @var Zend_View
     */
    protected $_view;

    public function __construct($charset = 'utf-8') {
        parent::__construct($charset);
        $this->_view = self::getDefaultView();
    }

    protected static function getDefaultView() {
        if (self::$_defaultView === null) {
            self::$_defaultView = new Zend_View();
            self::$_defaultView->setScriptPath(APPLICATION_PATH . '/modules/default/views/layouts/mails');
        }

        return self::$_defaultView;
    }

    /**
     * @param array $param an array with properties and values for parameters
     * @return \Portfolio_HtmlTemplateMailer 
     */
    public function setViewParam($param) {
        foreach ($param as $property => $value) {
            $this->_view->$property = $value;
        }

        return $this;
    }

    /**
     *
     * @param type $template The name of the template file.
     * @param type $encoding
     */
    public function sendHtmlTemplate($template, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
        $html = $this->_view->render($template);
        $this->setBodyHtml($html, $this->getCharset(), $encoding);
        $this->send();
    }

}