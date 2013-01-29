<?php

class Model_Acl extends Zend_Acl {

    public function __construct() {
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('user'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'user');

        $this->add(new Zend_Acl_Resource('default'))
                ->add(new Zend_Acl_Resource('default:index'), 'default')
                ->add(new Zend_Acl_Resource('default:error'), 'default')
                ->add(new Zend_Acl_Resource('default:auth'), 'default')
                ->add(new Zend_Acl_Resource('default:search'), 'default')
                ->add(new Zend_Acl_Resource('default:project'), 'default')
                ->add(new Zend_Acl_Resource('default:page'), 'default');
        
        $this->add(new Zend_Acl_Resource('webmaster-panel'))
                ->add(new Zend_Acl_Resource('webmaster-panel:index'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:auth'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:slideshow'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:social'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:project'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:page'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:url'), 'webmaster-panel')
                ->add(new Zend_Acl_Resource('webmaster-panel:xml'), 'webmaster-panel');
        
        $this->allow('guest', 'default:index', 'index');
        $this->allow('guest', 'default:error', 'error');
        $this->allow('guest', 'default:auth', 'login');
        $this->allow('guest', 'default:search', 'index');
        $this->allow('guest', 'webmaster-panel:auth', 'login');
        $this->allow('guest', 'default:project', array('index', 'view'));
        $this->allow('guest', 'default:page', 'index');
        
        $this->deny('user', 'default:auth', 'login');
        $this->deny('user', 'webmaster-panel:auth', 'login');
        
        $this->allow('user', 'default:auth', 'logout');
        
        $this->allow('admin', 'webmaster-panel:index', 'index');
        $this->allow('admin', 'webmaster-panel:auth', 'logout');
        $this->allow('admin', 'webmaster-panel:slideshow', array('index','create','edit','delete','update'));
        $this->allow('admin', 'webmaster-panel:social', array('index','create','edit','delete','update'));
        $this->allow('admin', 'webmaster-panel:project', array('index','create','edit','delete','update'));
        $this->allow('admin', 'webmaster-panel:page', array('index','create','edit','delete','update'));
        $this->allow('admin', 'webmaster-panel:url', array('index','generate'));
        $this->allow('admin', 'webmaster-panel:xml', array('index','generate-navigation'));
    }

}