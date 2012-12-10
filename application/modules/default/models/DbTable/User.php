<?php

class Default_Model_DbTable_User extends Zend_Db_Table_Abstract {

    protected $_name = 'user';
    protected $_primary = 'id';
    
    public function createUser($data) {
        $this->insert($data);
    }

}

