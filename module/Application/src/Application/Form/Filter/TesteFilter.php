<?php

/*
 * To change this license 
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Teste
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class TesteFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        
        $this->notEmpty('campo2');  
        
        
    }
}
