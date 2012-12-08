<?php

class Application_Model_Register {
    
    public function createUser($data) {
        $dbTableUser = new Application_Model_DbTable_User();
        $dbTableUser->insert($data);
    }

    public function updateUser($data, $id) {
        $dbTableUser = new Application_Model_DbTable_User();
        $dbTableUser->update($data, 'id = ' . $id);
    }

}

