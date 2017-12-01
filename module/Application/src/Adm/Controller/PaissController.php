<?php

/*
 * To change this license 
 */

namespace Adm\Controller;

use \Application\Controller\CrudController;

/**
 * Description of PaissController
 *
 */
class PaissController extends AdmCrudController {

    public function autoCompAction($column = '', $valor = '', $anotherFilters = []) {
        $data = $this->getRequest()->getPost()->toArray();
        return parent::autoCompAction($data['column'], $data['data']);
    }

}
