<?php

namespace Adm\Controller;

use \Application\Controller\CrudController;

/**
 * Description of CidadesController
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class CidadesController extends AdmCrudController {
    
    public function autoCompAction($column = '', $valor = '', $anotherFilters = []) {
        $data = $this->getRequest()->getPost()->toArray();
        
        return parent::autoCompAction('nomeCidade',$data['data']);
    }

}
