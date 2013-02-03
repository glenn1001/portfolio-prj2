<?php

class Portfolio_UrlRewrites {

    private $_dbTableUrlRewrite;
    private $_dbTableUrlRewriteParameter;

    public function __construct() {
        $this->_dbTableUrlRewrite = new Default_Model_DbTable_UrlRewrite();
        $this->_dbTableUrlRewriteParameter = new Default_Model_DbTable_UrlRewriteParameter();
    }

    
    /**
     * Start generating new URL rewrites 
     */
    public function rewrite() {
        $this->clearDatabase();
        $this->generateOtherUrls();
        $this->generateProjectUrls();
        $this->generatePageUrls();
    }

    /**
     * Delete all generated URL rewrites
     */
    private function clearDatabase() {
        $this->_dbTableUrlRewrite->delete("`generated`='Y'");
        $this->_dbTableUrlRewriteParameter->delete("`generated`='Y'");
    }

    /**
     * Generate all SEO URL's for the projects 
     */
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

    /**
     * Generate all SEO URL's for the project overview pages
     * @param object $dbTableProject The object of the DbTable project
     */
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

    /**
     * Generate all SEO URL's for the pages
     */
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

    /**
     * Generate the URL for the home page
     */
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