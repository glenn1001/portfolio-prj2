<?php

class Default_Model_DbTable_UrlRewrite extends Zend_Db_Table_Abstract {

    protected $_name = 'url_rewrite';
    protected $_primary = 'id';
    
    public function getUrlRewrites() {
        $dbTableUrlRewriteParameter = new Default_Model_DbTable_UrlRewriteParameter;
        $urlRewrites = $this->fetchAll();

        $return = array();
        foreach($urlRewrites as $urlRewrite) {
            $array = $urlRewrite->toArray();
            $object = new stdClass();
            foreach ($array as $key => $value) {
                $object->$key = $value;
            }
            
            $parameters = $dbTableUrlRewriteParameter->fetchAll("`url_rewrite_id`='" . $urlRewrite->id . "'");
            $object->parameters = $parameters;
            
            $return[] = $object;
        }
        
        return $return;
    }

}

