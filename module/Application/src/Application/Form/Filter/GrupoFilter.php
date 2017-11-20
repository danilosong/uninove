<?php

/*
 * License
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Grupo
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class GrupoFilter extends AbstractFilter{
    
    
    public function getFilters($ret) {
        
        $this->notEmpty('nome');  
        
        $this->notEmpty('status');        
        
    }
}
