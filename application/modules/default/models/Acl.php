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
                ->add(new Zend_Acl_Resource('default:page'), 'default');
        
        $this->add(new Zend_Acl_Resource('admn'))
                ->add(new Zend_Acl_Resource('admn:index'), 'admn')
                ->add(new Zend_Acl_Resource('admn:auth'), 'admn')
                ->add(new Zend_Acl_Resource('admn:page'), 'admn');;
        
        $this->allow('guest', 'default:index', 'index');
        $this->allow('guest', 'default:error', 'error');
        $this->allow('guest', 'default:auth', 'login');
        $this->allow('guest', 'admn:auth', 'login');
        $this->allow('guest', 'default:page', 'index');
        
        $this->deny('user', 'default:auth', 'login');
        $this->deny('user', 'admn:auth', 'login');
        
        $this->allow('user', 'default:auth', 'logout');
        
        $this->allow('admin', 'admn:index', 'index');
        $this->allow('admin', 'admn:auth', 'logout');
        $this->allow('admin', 'admn:page', array('index','create','edit','delete','update'));
    }

}