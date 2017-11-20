<?php

/*
 * To change this license 
 */

namespace Endereco\Controller;

/**
 * Description of CountrysController
 *
 * @author Paulo Watakabe
 */
class CountrysController extends \Application\Controller\CrudController {

    public function __construct() {
        parent::__construct('country','Endereco');
        $this->route = 'end/default'; 
        $this->routeAjax = "end/ajax"; 
    }

}
