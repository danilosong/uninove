<?php

/*
 * To change this license 
 */

namespace Adm\Form\Filter;

/**
 * Validação do form da Bairro
 * @author Allan Davini
 */
class BairroFilter extends \Application\Form\Filter\AbstractFilter{
    
    public function getFilters($ret) {
        if($ret){
            $this->notEmpty('nomeBairro');  
        }else{
            $this->notEmpty('nomeBairro');  
            $this->notEmpty('status');  
            $this->notEmpty('cidade');  
        }
    }
}
