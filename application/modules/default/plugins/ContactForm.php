<?php

class Plugin_ContactForm extends Zend_Controller_Plugin_Abstract {

    private $_view;
    private $_contactform;

    public function __construct() {
        $registry = Zend_Registry::getInstance();
        $this->_view = $registry->get('view');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $request = $this->getRequest();
        $this->_contactform = new Default_Form_Contact(null);
        if ($request->isPost()) {
            $post = $this->_request->getPost();
            if ($post['submit'] == 'Verzenden') {
                if ($this->_contactform->isValid($post)) {
                    $this->sendMessage();
                    $this->sendCopy();
                    $this->_contactform->reset();
                    $this->_view->contactSuccess = 'Bedankt voor uw bericht, ik zal hier z.s.m. op reageren.';
                }
            }
        }

        $this->_view->contactform = $this->_contactform;
    }

    /**
     * Sends the message to the webmaster.
     */
    private function sendMessage() {
        $data = $this->_contactform->getValues();

        $viewParam = array(
            'name' => $data['contactform_name'],
            'email' => $data['contactform_email'],
            'message' => nl2br(htmlentities($data['contactform_message'])),
            'ip' => $_SERVER['REMOTE_ADDR']
        );

        $registry = Zend_Registry::getInstance();
        $contactInfo = $registry->get('contactinfo');
        $email = $contactInfo['email'];
        $name = $contactInfo['name'];

        $mail = new Portfolio_HtmlTemplateMailer();
        $mail->setSubject($data['contactform_subject'])
                ->setFrom($data['contactform_email'], $data['contactform_name'])
                ->setReplyTo($data['contactform_email'], $data['contactform_name'])
                ->addTo($email, $name)
                ->setViewParam($viewParam)
                ->sendHtmlTemplate('contact.phtml');
    }

    /**
     * Sends a copy of the message to the consigner.
     */
    private function sendCopy() {
        $data = $this->_contactform->getValues();

        $viewParam = array(
            'domain' => $_SERVER['SERVER_NAME'],
            'email' => $data['contactform_email'],
            'subject' => $data['contactform_subject'],
            'message' => nl2br(htmlentities($data['contactform_message']))
        );

        $mail = new Portfolio_HtmlTemplateMailer();
        $mail->setSubject($data['contactform_subject'])
                ->addTo($data['contactform_email'], $data['contactform_name'])
                ->setViewParam($viewParam)
                ->sendHtmlTemplate('contact_copy.phtml');
    }

}