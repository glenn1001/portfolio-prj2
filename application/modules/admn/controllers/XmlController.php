<?php

class Admn_XmlController extends Zend_Controller_Action {

    private $_xml;
    private $_nav;
    private $_specialChars = array(
        '&' => '&amp;',
        '   ' => ' ',
        '  ' => ' '
    );

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function generateNavigationAction() {
        $this->_xml = new DOMDocument('1.0', 'utf-8');

        $config = $this->_xml->createElement('config');
        $this->_nav = $this->_xml->createElement('nav');

        $this->generateNavigationHome();
        $this->generateNavigationProjects();
        $this->generateNavigationPages();

        $config->appendChild($this->_nav);
        $this->_xml->appendChild($config);

        $output = $this->_xml->saveXML();

        $file = APPLICATION_PATH . '/modules/default/configs/navigation.xml';
        $this->writeXML($file, $output);
        
        $this->_redirect('/admn/xml/');
    }

    private function generateNavigationHome() {
        $home = $this->_xml->createElement('home');

        $label = $this->_xml->createElement('label', 'Home');
        $module = $this->_xml->createElement('module', 'default');
        $controller = $this->_xml->createElement('controller', 'index');
        $action = $this->_xml->createElement('action', 'index');
        $resource = $this->_xml->createElement('resource', 'default:index');
        $privilege = $this->_xml->createElement('privilege', 'index');

        $home->appendChild($label);
        $home->appendChild($module);
        $home->appendChild($controller);
        $home->appendChild($action);
        $home->appendChild($resource);
        $home->appendChild($privilege);

        $this->_nav->appendChild($home);
    }

    private function generateNavigationProjects() {
        $dbTableProject = new Default_Model_DbTable_Project();
        $projects = $dbTableProject->fetchAll("`status`='Y'  AND `menu`='Y'", 'pos DESC');

        $projectNav = $this->_xml->createElement('projects');
        $label = $this->_xml->createElement('label', 'Projecten');
        $uri = $this->_xml->createElement('uri', '/projecten/');
        $resource = $this->_xml->createElement('resource', 'default:project');
        $privilege = $this->_xml->createElement('privilege', 'index');
        if (count($projects) > 0) {
            $projectNavPages = $this->_xml->createElement('pages');
        }

        $counter = 1;
        foreach ($projects as $project) {
            $projectSubNav = $this->_xml->createElement('project' . $counter);

            $title = $project->title;
            foreach ($this->_specialChars as $key => $value) {
                $title = str_replace($key, $value, $title);
            }
            
            $labelSub = $this->_xml->createElement('label', $title);
            $uriSub = $this->_xml->createElement('uri', '/projecten' . $project->canonical);
            $resourceSub = $this->_xml->createElement('resource', 'default:project');
            $privilegeSub = $this->_xml->createElement('privilege', 'index');

            $projectSubNav->appendChild($labelSub);
            $projectSubNav->appendChild($uriSub);
            $projectSubNav->appendChild($resourceSub);
            $projectSubNav->appendChild($privilegeSub);

            $projectNavPages->appendChild($projectSubNav);
            unset($projectSubNav);
            $counter++;
        }

        $projectNav->appendChild($label);
        $projectNav->appendChild($uri);
        $projectNav->appendChild($resource);
        $projectNav->appendChild($privilege);
        if (count($projects) > 0) {
            $projectNav->appendChild($projectNavPages);
        }

        $this->_nav->appendChild($projectNav);
    }

    private function generateNavigationPages($parent_id = 0) {
        $dbTablePage = new Default_Model_DbTable_Page();
        $pages = $dbTablePage->fetchAll("`parent_id`='$parent_id' AND `status`='Y'  AND `menu`='Y'", 'pos ASC');

        $counter = 1;
        $subNavPages = array();
        foreach ($pages as $page) {
            $pageNav = $this->_xml->createElement('page' . $counter);
            
            $title = $page->title;
            foreach ($this->_specialChars as $key => $value) {
                $title = str_replace($key, $value, $title);
            }

            $label = $this->_xml->createElement('label', $title);
            $uri = $this->_xml->createElement('uri', $page->canonical);
            $resource = $this->_xml->createElement('resource', 'default:page');
            $privilege = $this->_xml->createElement('privilege', 'index');

            $pageNav->appendChild($label);
            $pageNav->appendChild($uri);
            $pageNav->appendChild($resource);
            $pageNav->appendChild($privilege);
            
            if (count($dbTablePage->fetchAll("`parent_id`='" . $page->id . "' AND `status`='Y'  AND `menu`='Y'")) > 0) {
                $pageNavPages = $this->_xml->createElement('pages');
                
                $subPages = $this->generateNavigationPages($page->id);
                foreach ($subPages as $subPage) {
                    $pageNavPages->appendChild($subPage);
                }
                
                $pageNav->appendChild($pageNavPages);
            }

            if ($parent_id == 0) {
                $this->_nav->appendChild($pageNav);
            } else {
                $subNavPages[] = $pageNav;
            }
            
            $counter++;
        }
        
        if ($parent_id != 0) {
            return $subNavPages;
        }
    }
    
    private function writeXML($file, $output) {
        $file = fopen($file, 'w');
        fwrite($file, $output);
        fclose($file);
    }

}

