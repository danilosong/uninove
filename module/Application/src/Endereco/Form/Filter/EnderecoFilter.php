<?php

/*
 * To change this license 
 */

namespace Endereco\Form\Filter;

/**
 * Validação do form do Endereco
 *
 * @author Paulo Cordeiro Watakabe <watakabe05@gmail.com>
 */
class EnderecoFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function __construct() {
        
        $this->notEmpty('enderecoCodigo');  
        $this->notEmpty('bairroCodigo');  
        $this->notEmpty('enderecoCep');  
        $this->notEmpty('enderecoLogradouro');  
         
        
    }
}
