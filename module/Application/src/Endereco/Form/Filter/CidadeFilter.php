<?php

/*
 * To change this license 
 */

namespace Endereco\Form\Filter;

/**
 * Validação do form do Cidade
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class CidadeFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('cidadeCodigo');  
        $this->notEmpty('ufCodigo');  
        $this->notEmpty('cidadeDescricao');  
        $this->notEmpty('cidadeCep');  
        
    }
}
