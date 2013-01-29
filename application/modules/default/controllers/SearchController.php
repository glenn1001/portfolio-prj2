<?php

class Default_SearchController extends Zend_Controller_Action {

    public function indexAction() {
        $post = $this->_request->getPost();
        $searchString = $post['search_keywords'];
        
        $search = new Portfolio_SearchEngine();
        $this->view->projects = $search->search(
            $searchString,
            'project',
            array('descr' => 1, 'meta_descr' => 2, 'meta_keywords' => 3, 'title' => 4),
            "`status`='Y'",
            'id',
            array('id', 'img', 'title', 'meta_descr', 'canonical')
        );
        
        $this->view->pages = $search->search(
            $searchString,
            'page',
            array('descr' => 1, 'meta_descr' => 2, 'meta_keywords' => 3, 'title' => 4),
            "`status`='Y'",
            'id',
            array('id', 'title', 'meta_descr', 'canonical')
        );
        
        $projectCount = count($this->view->projects);
        $pageCount = count($this->view->pages);
        
        $count = ($projectCount >= $pageCount ? $projectCount : $pageCount);
        $this->view->count = ($count > 10 ? 10 : $count);
    }

}

