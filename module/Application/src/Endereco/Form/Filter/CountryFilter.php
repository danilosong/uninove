<?php

/*
 * To change this license 
 */

namespace Endereco\Form\Filter;

/**
 * Validação do form do Country
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class CountryFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('iso');  
        $this->notEmpty('iso3');  
        $this->notEmpty('numcode');  
        $this->notEmpty('nome');  
        
    }
}
