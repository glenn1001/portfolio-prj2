<?php

class Portfolio_UrlRewrites {

    private $_dbTableUrlRewrite;
    private $_dbTableUrlRewriteParameter;
    private $_truncate = true;

    public function __construct() {
        $this->_dbTableUrlRewrite = new Default_Model_DbTable_UrlRewrite();
        $this->_dbTableUrlRewriteParameter = new Default_Model_DbTable_UrlRewriteParameter();
    }

    public function rewrite() {
        $this->clearDatabase();
        $this->generateOtherUrls();
        $this->generateProjectUrls();
        $this->generatePageUrls();
    }

    private function clearDatabase() {
        $this->_dbTableUrlRewrite->delete("`generated`='Y'");
        $this->_dbTableUrlRewriteParameter->delete("`generated`='Y'");
    }

    private function generateProjectUrls() {
        $dbTableProject = new Default_Model_DbTable_Project();
        $this->generateProjectOverviewUrls($dbTableProject);
        $projects = $dbTableProject->fetchAll();

        foreach ($projects as $project) {
            $this->_dbTableUrlRewrite->insert(array(
                'url' => '/projecten' . $project->canonical,
                'module' => 'default',
                'controller' => 'project',
                'action' => 'view',
                'generated' => 'Y'
            ));

            $lastId = $this->_dbTableUrlRewrite->getAdapter()->lastInsertId();

            $this->_dbTableUrlRewriteParameter->insert(array(
                'url_rewrite_id' => $lastId,
                'key' => 'id',
                'value' => $project->id,
                'generated' => 'Y'
            ));
        }
    }

    private function generateProjectOverviewUrls($dbTableProject) {
        $this->_dbTableUrlRewrite->insert(array(
            'url' => '/projecten/',
            'module' => 'default',
            'controller' => 'project',
            'action' => 'index',
            'generated' => 'Y'
        ));

        $totalProjects = count($dbTableProject->fetchAll("`status`='Y'"));
        $totalProjectPages = ceil($totalProjects / $dbTableProject->projectsPerPage);

        for ($i = 1; $i <= $totalProjectPages; $i++) {
            $this->_dbTableUrlRewrite->insert(array(
                'url' => '/projecten/' . $i . '/',
                'module' => 'default',
                'controller' => 'project',
                'action' => 'index',
                'generated' => 'Y'
            ));

            $lastId = $this->_dbTableUrlRewrite->getAdapter()->lastInsertId();

            $this->_dbTableUrlRewriteParameter->insert(array(
                'url_rewrite_id' => $lastId,
                'key' => 'pageid',
                'value' => $i,
                'generated' => 'Y'
            ));
        }
    }

    private function generatePageUrls() {
        $dbTablePage = new Default_Model_DbTable_Page();
        $pages = $dbTablePage->fetchAll();

        foreach ($pages as $page) {
            $this->_dbTableUrlRewrite->insert(array(
                'url' => $page->canonical,
                'module' => 'default',
                'controller' => 'page',
                'action' => 'index',
                'generated' => 'Y'
            ));

            $lastId = $this->_dbTableUrlRewrite->getAdapter()->lastInsertId();

            $this->_dbTableUrlRewriteParameter->insert(array(
                'url_rewrite_id' => $lastId,
                'key' => 'id',
                'value' => $page->id,
                'generated' => 'Y'
            ));
        }
    }

    private function generateOtherUrls() {
        $this->_dbTableUrlRewrite->insert(array(
            'url' => '/',
            'module' => 'default',
            'controller' => 'index',
            'action' => 'index',
            'generated' => 'Y'
        ));
    }

}

?>