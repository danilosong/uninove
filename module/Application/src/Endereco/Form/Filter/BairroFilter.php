<?php

/*
 * To change this license 
 */

namespace Endereco\Form\Filter;

/**
 * Validação do form do Bairro
 *
 * @author Danilo Dorotheu
 */
class CountryFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('bairroCodigo');  
        $this->notEmpty('cidadeCodigo');  
        $this->notEmpty('bairroDescricao');  
        
    }
}
