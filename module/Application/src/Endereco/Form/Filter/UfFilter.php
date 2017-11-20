<?php

/*
 * To change this license 
 */

namespace Endereco\Form\Filter;

/**
 * Validação do form do Uf
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class UfFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('ufCodigo');  
        $this->notEmpty('ufSigla');  
        $this->notEmpty('ufDescricao');  
         
        
    }
}
