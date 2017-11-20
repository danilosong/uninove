<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do AppRole
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class AppRoleFilter extends AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('nome');  
        
        $this->emptyTrue('parent');
        
        $this->emptyTrue('isAdmin');
        
    }
}
