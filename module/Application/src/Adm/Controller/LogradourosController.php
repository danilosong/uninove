<?php

namespace Adm\Controller;

/**
 * Description of LogradourosController
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class LogradourosController extends AdmCrudController {
    
    public function autoCompAction($column = '', $valor = '', $anotherFilters = []) {
        $data = $this->getRequest()->getPost()->toArray();
        return parent::autoCompAction($data['searchBy'],$data['data']);
    }

}
