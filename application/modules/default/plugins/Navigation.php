<?php

class Plugin_Navigation extends Zend_Controller_Plugin_Abstract {

    private $_acl;
    private $_view;
    private $_url;
    private $_skill_pageid = 1;
    private $_cv_pageid = 3;
    
    protected $_request;

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->_request = $request;

        $registry = Zend_Registry::getInstance();
        $this->_acl = $registry->get('acl');
        $this->_view = $registry->get('view');

        $this->generateHeaderNav();
        $this->generateLeftColumnNav();
    }

    /**
     * Generates the navigation for the top.
     */
    private function generateHeaderNav() {
        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH . '/modules/' . $this->_request->getModuleName() . '/configs/navigation.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);

        $this->_view->navigation($navContainer)->setAcl($this->_acl)->setRole(Zend_Registry::get('role'));
    }

    /**
     * Generates the navigation for the left column 
     */
    private function generateLeftColumnNav() {
        $this->_url = $this->getRequest()->getRequestUri();

        switch (true) {
            case strstr($this->_url, 'kennis-vaardigheden'):
                $this->_view->leftColNav = $this->getPages($this->_skill_pageid);
                break;
            case strstr($this->_url, 'cv'):
                $this->_view->leftColNav = $this->getPages($this->_cv_pageid);
                break;
            case strstr($this->_url, 'project'):
                $this->_view->leftColNav = $this->getProjects();
                break;
            default:
                $this->_view->leftColNav = $this->getPages();
                break;
        }
    }
    
    /**
     * This will return a string filled with an unordered list (ul) for the naviagtion on the project pages
     * @return string Returns string filled with the navigation
     */
    private function getPages($pageid = NULL) {
        $dbTablePage = new Default_Model_DbTable_Page();
        if ($pageid == NULL) {
            $pages = $dbTablePage->fetchAll("`parent_id`='0' AND `status`='Y'", 'pos ASC');
        } else {
            $pages = $dbTablePage->fetchAll("`id`='$pageid' AND `status`='Y'");
        }
        
        
        foreach ($pages as $key => $page) {
            $data[$key] = array(
                'item'      => $page,
                'children'  => $this->getChildrenPages($page->id)
            );
        }
        
        return $this->formatNav($data, true);
    }
    
    /**
     * Get all children pages of a page
     * @param integer $pageid The page ID 
     * @return array Returns an array with the children pages
     */
    private function getChildrenPages($pageid) {
        $dbTablePage = new Default_Model_DbTable_Page();
        $tmpPages = $dbTablePage->fetchAll("`parent_id`='$pageid' AND `status`='Y'");
        
        $pages = array();
        $i = 0;
        foreach ($tmpPages as $page) {
            $pages[$i]['item']        = $page;
            if (count($dbTablePage->fetchAll("`parent_id`='" . $page->id . "'")) > 0) {
                $pages[$i]['children']    = $this->getChildrenPages($page->id);
            }
            $i++;
        }
        
        return $pages;
    }

    /**
     * This will return a string filled with an unordered list (ul) for the naviagtion on the project pages
     * @return string Returns string filled with the navigation
     */
    private function getProjects() {
        $dbTableProject = new Default_Model_DbTable_Project();
        $tmpProjects = $dbTableProject->fetchAll("`status`='Y'", 'pos DESC');
        
        $projects = array();
        foreach ($tmpProjects as $project) {
            $project->canonical = '/projecten' . $project->canonical;
            $projects[]['item'] = $project;
        }
        
        $project = (object) array('canonical' => '/projecten/', 'title' => 'Projecten');
        
        $data[0] = array(
            'item'      => $project,
            'children'  => $projects
        );

        return $this->formatNav($data, true);
    }

    /**
     * Format the data to an string filled as an unordered list (ul).
     * @param array/object $data This is the data which will be used for the ul
     * @return string
     */
    private function formatNav($data, $first = false) {
        if ($first) {
            $nav = '<ul class="nav">';
        } else {
            $nav = '<ul>';
        }
        
        foreach ($data as $array) {
            $nav .= '<li>';
            if ($this->_url == $array['item']->canonical) {
                $nav .= '<a href="' . $array['item']->canonical . '" class="active">' . $array['item']->title . '</a>';
            } else {
                $nav .= '<a href="' . $array['item']->canonical . '">' . $array['item']->title . '</a>';
            }

            // check if this item has any children
            if (is_array($array['children'])) {
                $nav .= $this->formatNav($array['children']);
            }
            $nav .= '</li>';
        }
        $nav .= '</ul>';

        return $nav;
    }

}