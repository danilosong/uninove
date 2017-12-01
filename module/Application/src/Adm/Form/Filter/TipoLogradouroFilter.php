<?php

/*
 * To change this license 
 */

namespace Adm\Form\Filter;

/**
 * Validação do form da TipoLogradouro
 * @author Allan Davini
 */
class TipoLogradouroFilter extends AdmAbstractFilter{
    
    public function getFilters($ret) {
//        
//        $this->notEmpty('tipo');  
//        if($ret == FALSE){
//            $this->notEmpty('status');  
//        }else{
//            $this->emptyTrue('status');
//        }
        
        if($ret){
            $this->notEmpty('tipo');  
        }else{
            $this->notEmpty('tipo');  
            $this->notEmpty('status');  
            $this->notEmpty('sigla');  
        }
    }
}
