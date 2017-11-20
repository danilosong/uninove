<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do AppMenu
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class AppMenuFilter extends AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('descricao');  
        $this->notEmpty('label');  
        $this->notEmpty('ordem');  
        
        $this->emptyTrue('inMenu');
        
        $this->emptyTrue('isAdmin');
        
    }
}
