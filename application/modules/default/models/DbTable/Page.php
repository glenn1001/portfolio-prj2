<?php

class Default_Model_DbTable_Page extends Zend_Db_Table_Abstract {

    protected $_name = 'page';
    protected $_primary = 'id';

    public function getPagesByParent($parent_id = null) {
        $pages = $this->fetchAll("`parent_id`='$parent_id'", 'pos ASC');
        $newPages = array();
        foreach ($pages as $page) {
            $array = $page->toArray();
            $array['path'] = $this->getPath($page->id);

            $object = new stdClass();
            foreach ($array as $key => $value) {
                $object->$key = $value;
            }
            
            $newPages[] = $object;
            $childPages = $this->getPagesByParent($page->id);
            foreach ($childPages as $childPage) {
                $newPages[] = $childPage;
            }
        }

        return $newPages;
    }

    public function getPath($id, $path = '') {
        $page = $this->fetchAll("`id`='$id'");
        $path = ($path == '' ? $page[0]->title : $page[0]->title . ' - ' . $path);
        if ($page[0]->parent_id != 0) {
            $path = $this->getPath($page[0]->parent_id, $path);
        }

        return $path;
    }

}

