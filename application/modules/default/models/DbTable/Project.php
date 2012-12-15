<?php

class Default_Model_DbTable_Project extends Zend_Db_Table_Abstract {

    protected $_name = 'project';
    protected $_primary = 'id';
    
    public $_projectsPerPage = '6';
    
    /**
     *
     * @param string $pageid The ID of the current page.
     * @return array Returns array with projects for this page.
     */
    public function getProjects($pageid) {
        $projects = $this->fetchAll(null, 'pos DESC');
        $return = array();
        
        $start = ($pageid - 1) * $this->_projectsPerPage;
        for ($i = $start; $i < ($start + $this->_projectsPerPage); $i++) {
            if (isset($projects[$i])) {
                $return[] = $projects[$i];
            }
        }
        
        return $return;
    }

}

