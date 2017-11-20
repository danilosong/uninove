<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of BairrosController
 *
 * @author Danilo Dorotheu
 */
class BairrosController extends \Application\Controller\CrudController {

    public function __construct() {
        parent::__construct('bairro', 'Endereco');
        $this->route = 'end/default';
        $this->routeAjax = "end/ajax";
    }

}
