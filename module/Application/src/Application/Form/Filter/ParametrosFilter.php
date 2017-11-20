<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form\Filter;

/**
 * Validação do form do Users
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class ParametrosFilter extends AbstractFilter{
    
    public function getFilters($ret) {
        
        $this->notEmpty('chave');  
        
        $this->notEmpty('conteudo');        
        
        $this->notEmpty('descricao');
               
    }
}
