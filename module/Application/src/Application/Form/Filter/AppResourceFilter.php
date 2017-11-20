<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do AppResource
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class AppResourceFilter extends AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('name');  
        
    }
}
