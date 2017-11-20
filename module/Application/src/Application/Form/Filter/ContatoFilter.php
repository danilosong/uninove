<?php

/*
 * License
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Contato
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class ContatoFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        
        $this->notEmpty('contatoUser');  
        
    }
}
