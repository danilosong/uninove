<?php

namespace Adm\Controller;

/**
 * Description of IndexController
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class IndexController extends AdmCrudController {


    public function buildIndexAction() {
        $data = $this->getRequest()->getPost()->toArray();
        
        return $this->makeView(compact("data"));
    }
    
    public function getRouteAction() {
        $dataView = $this->getDataView('rota para abas');
        $data = $this->getRequest()->getPost()->toArray();
        
        return $this->makeView(compact("data","dataView"));        
    }

}
