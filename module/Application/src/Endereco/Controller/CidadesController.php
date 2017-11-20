<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of CidadesController
 *
 * @author Paulo Watakabe
 */
class CidadesController extends \Application\Controller\CrudController {

    public function __construct() {
        parent::__construct('cidade','Endereco');
        $this->route = 'end/default'; 
        $this->routeAjax = "end/ajax"; 
    }

}
