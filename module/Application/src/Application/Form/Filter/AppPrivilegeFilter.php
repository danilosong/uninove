<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do AppPrivilege
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class AppPrivilegeFilter extends AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('nome');  
        $this->notEmpty('role');  
        $this->notEmpty('resource');  
        
    }
}
