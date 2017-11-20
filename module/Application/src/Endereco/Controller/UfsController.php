<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of UfsController
 *
 * @author Paulo Watakabe
 */
class UfsController extends \Application\Controller\CrudController {

    public function __construct() {
        parent::__construct('uf','Endereco');
        $this->route = 'end/default'; 
        $this->routeAjax = "end/ajax"; 
    }

}
