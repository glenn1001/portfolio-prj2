<?php

class WebmasterPanel_AuthController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function loginAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/webmaster-panel/');
        }

        $request = $this->getRequest();
        $form = new WebmasterPanel_Form_Login();

        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $authAdapter = $this->getAuthAdapter();

                $email = $form->getValue('email');
                $password = $form->getValue('password');

                $authAdapter->setIdentity($email)
                        ->setCredential($password);

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject();

                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    $this->_redirect('/webmaster-panel/');
                } else {
                    $this->view->errorMessage = 'User name and password combination is incorrect';
                }
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/webmaster-panel/auth/login/');
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('user')
                ->setIdentityColumn('email')
                ->setCredentialColumn('password')
                ->setCredentialTreatment('MD5(?)');

        return $authAdapter;
    }

}

