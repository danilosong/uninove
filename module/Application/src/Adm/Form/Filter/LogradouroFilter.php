<?php

namespace Adm\Form\Filter;

/**
 * Validação do form da Logradouro
 * @author Danilo Dorotheu <danilo.dorotheu@live.com>
 */
class LogradouroFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function getFilters($ret) {
        $this->notEmpty('nomeLogradouro');
        $this->notEmpty('cep');
        
        if($ret == FALSE){
            $this->notEmpty('bairro');  
            $this->notEmpty('tipoLogradouro');  
            $this->notEmpty('status');  
        }  
    }
}
